<?php
/*
  Template Name:登录注册
 */
wp_enqueue_style('custom-login.css', get_stylesheet_directory_uri() . '/custom-login.css');
get_header();
?>
<div id="primary">
    <div id="content" role="main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>
            <!-- .entry-header -->
            <div class="entry-content">
                <!-- Theme Template Code -->
                <div id="login-register-password">
                    <?php global $user_ID, $user_identity;
                    get_currentuserinfo();
                    if (!$user_ID) { ?>
                        <ul class="tabs_login">
                            <li class="active_login"><a href="#tab1_login"><?php _e('登陆'); ?></a></li>
                            <li><a href="#tab2_login"><?php _e('注册'); ?></a></li>
                            <li><a href="#tab3_login"><?php _e('找回密码'); ?></a></li>
                        </ul>
                        <div class="tab_container_login">
                            <div id="tab1_login" class="tab_content_login">
                                <?php $register = $_GET['register'];
                                $reset = $_GET['reset'];
                                if ($register == true) { ?>
                                    <?php _e('登陆成功!'); ?>
                                    <?php _e('密码已经发送至您的邮箱，请获取密码后登陆'); ?>
                                <?php } elseif ($reset == true) { ?>
                                    <?php _e('登陆成功!'); ?>
                                    <?php _e('重设秘密的链接已发送至您的邮箱，请查看'); ?>
                                <?php } else { ?>
                                    <?php _e('已经注册'); ?>
                                    <?php _e('马上登陆'); ?></em>
                                <?php } ?>
                                <form name="loginform" id="loginform" action="<?php echo esc_url(site_url('wp-login.php', 'login_post')); ?>" method="post">
                                    <div class="username">
                                        <label for="user_login"><?php _e('Username'); ?>: </label>
                                        <input type="text" name="log" value="<?php echo esc_attr(stripslashes($user_login)); ?>" size="20" id="user_login" tabindex="11" />
                                    </div>
                                    <div class="password">
                                        <label for="user_pass"><?php _e('Password'); ?>: </label>
                                        <input type="password" name="pwd" value="" size="20" id="user_pass" tabindex="12" />
                                    </div>
                                    <div class="login_fields">
                                        <div class="rememberme">
                                            <label for="rememberme">
                                                <input type="checkbox" name="rememberme" value="forever" checked="checked" id="rememberme" tabindex="13" /> <?php _e('记住我'); ?>
                                            </label>
                                        </div>
                                         <?php do_action('login_form'); ?>
                                        <input type="submit" name="user-submit" value="<?php _e('登陆'); ?>" tabindex="14" class="user-submit" />
                                        <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
                                        <input type="hidden" name="user-cookie" value="1" />
                                    </div>
                                </form>
                            </div>
                            <div id="tab2_login" class="tab_content_login" style="display:none;">
                            <?php _e('注册'); ?>
                            <?php _e('免费注册，获取意想不到的资源'); ?>
                                <form name="registerform" id="registerform" action="<?php echo esc_url(site_url('wp-login.php?action=register', 'login_post')); ?>" method="post">
                                    <div class="username">
                                        <label for="user_login"><?php _e('用户名'); ?>: </label>
                                        <input type="text" name="user_login" value="<?php echo esc_attr(stripslashes($user_login)); ?>" size="20" id="user_login" tabindex="101" />
                                    </div>
                                    <div class="password">
                                        <label for="user_email"><?php _e('邮箱'); ?>: </label>
                                        <input type="text" name="user_email" value="<?php echo esc_attr(stripslashes($user_email)); ?>" size="25" id="user_email" tabindex="102" />
                                    </div>
                                    <div class="login_fields">
                                        <?php do_action('register_form'); ?>
                                        <input type="submit" name="user-submit" value="<?php _e('注册！'); ?>" class="user-submit" tabindex="103" />
                                        <?php $register = $_GET['register'];
                                            if ($register == true) {
                                                echo ' 密码已发送至您的邮箱，请查看 ';
                                            }
                                        ?>
                                        <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?register=true" />
                                        <input type="hidden" name="user-cookie" value="1" />
                                    </div>
                                </form>
                            </div>
                            <div id="tab3_login" class="tab_content_login" style="display:none;">
                                <?php _e('忘记密码？'); ?>
                                <?php _e('填写您的用户名或注册邮箱来找回密码'); ?>
                                <form name="lostpasswordform" id="lostpasswordform" action="<?php echo esc_url(site_url('wp-login.php?action=lostpassword', 'login_post')); ?>" method="post">
                                    <div class="username">
                                        <label for="user_login" class="hide"><?php _e('用户名或邮箱'); ?>: </label>
                                        <input type="text" name="user_login" value="" size="20" id="user_login" tabindex="1001" />
                                    </div>
                                    <div class="login_fields">
                                    <?php do_action('login_form', 'resetpass'); ?>
                                        <input type="submit" name="user-submit" value="<?php _e('重设密码'); ?>" class="user-submit" tabindex="1002" />
                                        <?php $reset = $_GET['reset'];
                                            if ($reset == true) {
                                                echo '信息将会发送至您的注册邮箱 ';
                                            }
                                        ?>
                                        <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?reset=true" />
                                        <input type="hidden" name="user-cookie" value="1" />
                                    </div>
                                </form>
                            </div>
                        </div>
                            <?php } else { // is logged in ?>
                        <div class="sidebox">
                        <?php _e('欢迎'); ?>, <?php echo $user_identity; ?>
                            <div class="usericon">
                                <?php global $userdata;
                                get_currentuserinfo();
                                echo get_avatar($userdata->ID, 60); ?>
                            </div>
                            <div class="userinfo">
                                 <?php _e('您已登录为'); ?> <strong><?php echo $user_identity; ?></strong>
                                <a href="<?php echo wp_logout_url('index.php'); ?>"><?php _e('登出'); ?></a>
                                <?php
                                if (current_user_can('manage_options')) {
                                    echo '<a href="' . admin_url() . '">' . __('控制板') . '</a>';
                                } else {
                                    echo '<a href="' . admin_url() . 'profile.php">' . __('编辑个人资料') . '</a>';
                                }
                                ?>
                            </div>
                        </div>
        <?php } ?></div>
                <!-- Custom Login/Register/Password  -->
            </div>
            <!-- .entry-content -->
        </article>
        <!-- #post-<?php the_ID(); ?> -->
    </div>
    <!-- #content -->
</div>
<!-- #primary -->

<?php get_footer(); ?>