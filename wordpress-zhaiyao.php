<?php
/**
 * 文章/公告摘要缓存：wp_post_excerpt_cache
 * 双层缓存：DB表 + 1/5000概率惰性刷新(shutdown后台落库)
 * 功能：save_post入库 + posts_results批量预加载 + 模板读取函数 + 删除清理
 */

if (!function_exists('auto_save_archive_excerpt')) {
    add_action('save_post', 'auto_save_archive_excerpt',10,3);
    /**
     * @param int $post_id
     * @param WP_Post $post
     * @param bool $update
     */
    function auto_save_archive_excerpt($post_id, $post, $update) {
        global $wpdb;
        $cache_table = $wpdb->prefix . 'post_excerpt_cache';

        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
            return;
        }

        if ($post->post_status !== 'publish') {
            $wpdb->delete($cache_table, ['post_id' => $post_id], ['%d']);
            return;
        }

        $now = time();
        $short_290 = null;
        $short_65  = null;

        if ($post->post_type === 'post') {
            if (!empty($post->post_excerpt)) {
                $raw = $post->post_excerpt;
            } else {
                $raw = wp_strip_all_tags($post->post_content, true);
            }
            $short_290 = mb_strimwidth(trim($raw), 0, 290, '...');
        }

        if ($post->post_type === 'bulletin') {
            if(!empty($post->post_excerpt)){
                $raw = $post->post_excerpt;
            }else{
                $raw = wp_strip_all_tags($post->post_content, true);
            }
            $raw = trim($raw);
            $short_65 = mb_strimwidth($raw, 0, 65, '...');
        }

        $wpdb->query($wpdb->prepare("
            INSERT INTO {$cache_table}(post_id,post_type,short_290,short_65,update_time)
            VALUES (%d,%s,%s,%s,%d)
            ON DUPLICATE KEY UPDATE
                post_type = VALUES(post_type),
                short_290 = COALESCE(VALUES(short_290), short_290),
                short_65  = COALESCE(VALUES(short_65), short_65),
                update_time = %d
        ",
            $post_id, $post->post_type, $short_290, $short_65, $now,
            $now
        ));
    }
}

// 惰性刷新待处理ID容器
$GLOBALS['_excerpt_lazy_refresh_ids'] = [];

add_filter('posts_results', function($posts, $query) {
    global $wpdb;

    // 只有携带 _excerpt_load=true 的查询才执行摘要预加载，其余全部跳过
    if ( is_admin() || ! $query->get('_excerpt_load', false) || $query->is_single() || $query->is_page() ) {
        return $posts;
    }

    if ( empty($posts) ) {
        return $posts;
    }

    $table_cache = $wpdb->prefix . 'post_excerpt_cache';
    $ids = array();
    foreach ($posts as $p) {
        $ids[] = (int)$p->ID;
    }
    $ids_str = implode(',', array_map('intval', $ids));

    if ( ! isset($GLOBALS['_cached_post_excerpt']) ) {
        $GLOBALS['_cached_post_excerpt'] = [];
    }

    // 读取 update_time 用于惰性刷新过期判断
    $rows = $wpdb->get_results("
        SELECT post_id, post_type, short_290, short_65, update_time
        FROM {$table_cache}
        WHERE post_id IN({$ids_str})
    ");

    foreach ($rows as $r) {
        $pid = (int)$r->post_id;
        $GLOBALS['_cached_post_excerpt'][$pid] = $r;

        // 1/5000概率触发惰性刷新判断，超过1天才刷新
        $prob = 5000;
        if (mt_rand(1, $prob) === 1) {
            $expire_second = 86400;
            if ((time() - $r->update_time) > $expire_second) {
                $GLOBALS['_excerpt_lazy_refresh_ids'][] = $pid;
            }
        }
    }

    return $posts;
}, 10, 2);

/**
 * shutdown钩子：页面输出完成后后台执行刷新，不阻塞前端响应
 */
add_action('shutdown', function () {
    $refresh_ids = $GLOBALS['_excerpt_lazy_refresh_ids'] ?? [];
    if (empty($refresh_ids)) {
        return;
    }
    $refresh_ids = array_unique(array_map('intval',$refresh_ids));

    $max_once = 5; //单次请求最多刷新5条，控制DB压力
    $refresh_ids = array_slice($refresh_ids, 0, $max_once);

    foreach ($refresh_ids as $pid) {
        $post = get_post($pid);
        if (!$post || $post->post_status !== 'publish') {
            continue;
        }
        auto_save_archive_excerpt($pid, $post, true);
    }
});

/**
 * 模板调用：直接从内存数组拿摘要，不再访问数据库
 * 仅在WP主查询the_loop内降级有效；外部调用无缓存返回空
 * @param int $post_id
 * @return string
 */
function template_get_cached_excerpt_memory($post_id){
    global $post;
    $arr = $GLOBALS['_cached_post_excerpt'] ?? [];
    if(isset($arr[$post_id]) && !empty($arr[$post_id]->short_290)){
        return $arr[$post_id]->short_290;
    }

    // 降级：仅当前loop的post对象匹配才现场截取，不发起数据库查询
    if( ! $post || $post->ID !== $post_id ){
        return '';
    }
    if(!empty($post->post_excerpt)){
        $raw = $post->post_excerpt;
    }else{
        $raw = wp_strip_all_tags($post->post_content, true);
    }
    return mb_strimwidth(trim($raw),0,290,'...');
}

/**
 * 公告：从内存数组读取 short_65，不查MySQL
 * @param int $post_id
 * @return string
 */
function template_get_bulletin_excerpt_memory(int $post_id): string
{
    $cache_arr = $GLOBALS['_cached_post_excerpt'] ?? [];
    if (isset($cache_arr[$post_id]) && !empty($cache_arr[$post_id]->short_65)) {
        return $cache_arr[$post_id]->short_65;
    }

    // ========= 可选开启降级：无缓存现场截取65字符，需要就取消下面注释 =========
    /*
    global $post;
    if ($post && $post->ID === $post_id && $post->post_type === 'bulletin') {
        $raw = !empty($post->post_excerpt) ? $post->post_excerpt : wp_strip_all_tags($post->post_content, true);
        return mb_strimwidth(trim($raw),0,65,'...');
    }
    */
    return '';
}

// 永久删除文章，清理摘要表
add_action('delete_post', function($post_id) {
    global $wpdb;
    $cache_table = $wpdb->prefix . 'post_excerpt_cache';
    $wpdb->delete($cache_table, ['post_id' => $post_id], ['%d']);
});
