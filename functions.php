<?php if (get_option('cx_thumbnails') == 'true') { ?>
<?php get_template_part( '/inc/functions/post-thumbnails' ); ?>
<?php } ?>
<?php

// 小工具
if (function_exists('register_sidebar')){
	register_sidebar( array(
		'name'          => '首页侧边栏',
		'id'            => 'sidebar-1',
		'description'   => '显示在首页及分类归档页',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><p><i class="icon-st"></i></p>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '正文侧边栏',
		'id'            => 'sidebar-2',
		'description'   => '显示在正文、页面',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><p><i class="icon-st"></i></p>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '分类归档侧边栏',
		'id'            => 'sidebar-5',
		'description'   => '显示在归档页、搜索、404页 ',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><p><i class="icon-st"></i></p>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '正文底部小工具',
		'id'            => 'sidebar-3',
		'description'   => '显示在正文底部',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><div class="s-icon"></div>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '正文跟随滚动',
		'id'            => 'sidebar-4',
		'description'   => '显示在正文侧边跟随模块',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><p><i class="icon-st"></i></p>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '首页、分类归档跟随滚动',
		'id'            => 'sidebar-6',
		'description'   => '显示在首页、归档页、搜索、404页 ',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><p><i class="icon-st"></i></p>',
		'after_title'   => '</h3>',
	) );
}

// 自定义菜单
register_nav_menus(
   array(
      'top-menu' => __( '顶部菜单' ),
      'header-menu' => __( '导航菜单' ),
      'mini-menu' => __( '移动版菜单' )
   )
);

// 背景
add_theme_support( 'custom-background' );

// 文章形式
add_theme_support( 'post-formats', array(
	'aside', 'image',
) );

// feed
add_theme_support( 'automatic-feed-links' );

// 加载前端脚本及样式
function ality_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri(), array(), '2016.2.20' );
	wp_enqueue_style( 'mediaqueries', get_template_directory_uri() . '/css/mediaqueries.css', array(), '1.0' );
	if ( is_singular() ) {
        wp_enqueue_style( 'highlight', get_template_directory_uri() . '/css/highlight.css', array(), '1.0');
	}
        wp_enqueue_script( 'jquery.min', get_template_directory_uri() . '/js/jquery.min.js', array(), '1.10.1', false );
		wp_enqueue_script( 'script', get_template_directory_uri() . '/js/script.js', array(), '1.0', false );
	if ( is_singular() ) {
		wp_localize_script( 'script', 'wpl_ajax_url', admin_url() . "admin-ajax.php");
		wp_enqueue_script( 'jquery.fancybox', get_template_directory_uri() . '/js/jquery.fancybox.js', array(), '2.15', false);
        wp_enqueue_script( 'comments-ajax', get_template_directory_uri() . '/js/comments-ajax.js', array(), '1.3', false);
	}
	// 加载回复js
	// if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	// 	wp_enqueue_script( 'comment-reply' );
	// }
}
add_action( 'wp_enqueue_scripts', 'ality_scripts' );

// 页脚加载JS
function footerScript() {
	wp_register_script( 'jquery.sidr.min', get_template_directory_uri() . '/js/jquery.sidr.min.js', false, '1.2.1', true );
	if ( !is_admin() ) {
	wp_enqueue_script( 'jquery.sidr.min' );
	}
}
add_action( 'init', 'footerScript' );

// 移除头部冗余代码
remove_action( 'wp_head', 'wp_generator' );// WP版本信息
remove_action( 'wp_head', 'rsd_link' );// 离线编辑器接口
remove_action( 'wp_head', 'wlwmanifest_link' );// 同上
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );// 上下文章的url
remove_action( 'wp_head', 'feed_links', 2 );// 文章和评论feed
remove_action( 'wp_head', 'feed_links_extra', 3 );// 去除评论feed
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );// 短链接

// 自动缩略图
function catch_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){
		echo get_bloginfo ( 'stylesheet_directory' );
		echo '/img/default.jpg';
  }
  return $first_img;
}

// 所有图片
function all_img($soContent){
	$soImages = '~<img [^\>]*\ />~';
	preg_match_all( $soImages, $soContent, $thePics );
	$allPics = count($thePics);
	if( $allPics > 0 ){ 
		$count=0;
			foreach($thePics[0] as $v){
				 if( $count == 4 ){break;}
				 else {echo $v;}
				$count++;
			}
	}
}

// 禁止无中文留言
if ( is_user_logged_in() ) {
} else {
function refused_spam_comments( $comment_data ) {
	$pattern = '/[一-龥]/u';  
	if(!preg_match($pattern,$comment_data['comment_content'])) {
		err('评论必须含中文！');
	}
	return( $comment_data );
}
add_filter('preprocess_comment','refused_spam_comments');
}

// 评论链接新窗口
function commentauthor($comment_ID = 0) {
    $url    = get_comment_author_url( $comment_ID );
    $author = get_comment_author( $comment_ID );
    if ( empty( $url ) || 'http://' == $url )
		echo $author;
    else
		echo "<a href='$url' rel='external nofollow' target='_blank' class='url'>$author</a>";
}

// 主题小工具
require get_template_directory() . '/inc/functions/widgets.php';

// 主题设置
require get_template_directory() . '/inc/theme-options.php';
require get_template_directory() . '/inc/guide.php';

// 评论模板
require get_template_directory() . '/inc/functions/comment-template.php';

// 评论通知
require get_template_directory() . '/inc/functions/notify.php';

// 热门文章
require get_template_directory() . '/inc/functions/hot-post.php';

// 分页
require get_template_directory() . '/inc/functions/pagenavi.php';

// 面包屑导航
require get_template_directory() . '/inc/functions/breadcrumb.php';

// 图片属性
require get_template_directory() . '/inc/functions/addclass.php';

// 文章类型
require get_template_directory() . '/inc/functions/post-type.php';

// 文字展开
require get_template_directory() . '/inc/functions/section.php';

// 禁止代码标点转换
remove_filter( 'the_content', 'wptexturize' );

// 链接管理
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

// 默认菜单
function default_menu() {
require get_template_directory() . '/inc/default-menu.php';
}

// 滚动加载
if (get_option('cx_scroll') == 'true') {
	require get_template_directory() . '/inc/functions/infinite-scroll.php';

	function footerscroll() {
		wp_register_script('infinite_scroll', get_template_directory_uri() . '/js/jquery.infinitescroll.min.js', false, '2.0.2', true );
		if ( is_home() || is_category()) {
		wp_enqueue_script( 'infinite_scroll' );
		}
	}
	add_action( 'wp_enqueue_scripts', 'footerscroll' );
}

// 分页
function ality_page_nav( ) {
	global $wp_query;
	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="nav-below">
			<div class="nav-next"><?php previous_posts_link( 上一页 ); ?></div>
			<div class="nav-previous"><?php next_posts_link( 下一页 ); ?></div>
		</nav>
	<?php endif;
}

// 去掉描述P标签
function deletehtml($description) {
	$description = trim($description);
	$description = strip_tags($description,"");
	return ($description);
}
add_filter('category_description', 'deletehtml');

// 禁止后台加载谷歌字体
function wp_remove_open_sans_from_wp_core() {
	wp_deregister_style( 'open-sans' );
	wp_register_style( 'open-sans', false );
	wp_enqueue_style('open-sans','');
}
add_action( 'init', 'wp_remove_open_sans_from_wp_core' );

// 字数统计
function count_words ($text) {
	global $post;
	if ( '' == $text ) {
	   $text = $post->post_content;
	   if (mb_strlen($output, 'UTF-8') < mb_strlen($text, 'UTF-8')) $output .= '共 ' . mb_strlen(preg_replace('/\s/','',html_entity_decode(strip_tags($post->post_content))),'UTF-8') . '字';
	   return $output;
	}
}

// 评论贴图
function cx_images($comment) {
	$size = 'auto';
	$comment = preg_replace(array('#(http://([^\s]*)\.(jpg|gif|png|JPG|GIF|PNG))#','#(https://([^\s]*)\.(jpg|gif|png|JPG|GIF|PNG))#'),'<img src="$1" alt="评论" style="width:'.$size.'; height:'.$size.'" />', $comment);
	return $comment;
}
add_filter('comment_text', 'cx_images');

// 下载按钮
function button_a($atts, $content = null) {
return '<div id="down"><a id="load" title="下载链接" href="#button_file"><i class="icon-down"></i>下载地址</a><div class="clear"></div></div>';
}
add_shortcode("file", "button_a");

// 编辑器增强
 function enable_more_buttons($buttons) {
	$buttons[] = 'hr';
	$buttons[] = 'del';
	$buttons[] = 'sub';
	$buttons[] = 'sup';
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'cleanup';
	$buttons[] = 'styleselect';
	$buttons[] = 'wp_page';
	$buttons[] = 'anchor';
	$buttons[] = 'backcolor';
	return $buttons;
}
add_filter( "mce_buttons_3", "enable_more_buttons" );

// 添加按钮
add_action('after_wp_tiny_mce', 'bolo_after_wp_tiny_mce');
function bolo_after_wp_tiny_mce($mce_settings) {
?>
<script type="text/javascript">
QTags.addButton( 'file', '下载按钮', "[file]" );
QTags.addButton( 'videos', '添加视频', "[videos]视频分享代码[/videos]" );
function bolo_QTnextpage_arg1() {
}
</script>
<?php }

// 视频
function screening($atts, $content=null){
	return '<div class="screening"><a class="video" href="'.$content.'">播放视频</a></div>';
}
add_shortcode('videos','screening');

// 后台预览
add_editor_style( '/css/editor-style.css' );

// 跳转到设置
if (is_admin() && $_GET['activated'] == 'true') {
header("Location: themes.php?page=theme-options.php");
}
// 禁用工具栏
show_admin_bar(false);

// taxonomy标题
function setTitle(){
    $term = get_term_by('slug',get_query_var('term'),get_query_var('taxonomy'));
    echo $title = $term->name;
}

// 点赞
add_action('wp_ajax_nopriv_ality_ding', 'ality_ding');
add_action('wp_ajax_ality_ding', 'ality_ding');
function ality_ding(){
    global $wpdb,$post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    if ( $action == 'ding'){
	    $bigfa_raters = get_post_meta($id,'ality_like',true);
	    $expire = time() + 99999999;
	    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
	    setcookie('ality_like_'.$id,$id,$expire,'/',$domain,false);
	    if (!$bigfa_raters || !is_numeric($bigfa_raters)) {
			update_post_meta($id, 'ality_like', 1);
		}
	    else {
			update_post_meta($id, 'ality_like', ($bigfa_raters + 1));
		}
		echo get_post_meta($id,'ality_like',true);
    }
    die;
}
// 彩色标签云
function colorCloud($text) {
	$text = preg_replace_callback('|<a (.+?)>|i', 'colorCloudCallback', $text);
	return $text;
}
function colorCloudCallback($matches) {
	$text = $matches[1];
	$color = dechex(rand(0,16777215));
	$pattern = '/style=(\'|\")(.*)(\'|\")/i';
	$text = preg_replace($pattern, "style=\"color:#{$color};$2;\"", $text);
	return "<a $text>";
}
add_filter('wp_tag_cloud', 'colorCloud', 1);

// 后台字体
function admin_lettering(){
    echo'<style type="text/css">
     body{ font-family: Microsoft YaHei;}
    </style>';
    }
add_action('admin_head', 'admin_lettering');

// 全部结束
?>