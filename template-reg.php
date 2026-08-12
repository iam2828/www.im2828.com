<?php
/*
Template Name: 用户注册
*/
?>
<?php
	if( !empty($_POST['user_reg']) ) {
		$error = '';
		$sanitized_user_login = sanitize_user( $_POST['user_login'] );
		$user_email = apply_filters( 'user_registration_email', $_POST['user_email'] );

  // 检查名称
	if ( $sanitized_user_login == '' ) {
		$error .= '<strong>错误</strong>：请输入用户名。<br />';
	} elseif ( ! validate_username( $sanitized_user_login ) ) {
		$error .= '<strong>错误</strong>：此用户名包含无效字符，请输入有效的用户名<br />。';
		$sanitized_user_login = '';
	} elseif ( username_exists( $sanitized_user_login ) ) {
		$error .= '<strong>错误</strong>：该用户名已被注册，请再选择一个。<br />';
	}

  // 检查邮件
	if ( $user_email == '' ) {
		$error .= '<strong>错误</strong>：请填写电子邮件地址。<br />';
	} elseif ( ! is_email( $user_email ) ) {
		$error .= '<strong>错误</strong>：电子邮件地址不正确。！<br />';
		$user_email = '';
	} elseif ( email_exists( $user_email ) ) {
		$error .= '<strong>错误</strong>：该电子邮件地址已经被注册，请换一个。<br />';
	}
   
  // 检查密码
	if(strlen($_POST['user_pass']) < 6)
		$error .= '<strong>错误</strong>：密码长度至少6位!<br />';
		elseif($_POST['user_pass'] != $_POST['user_pass2'])
		$error .= '<strong>错误</strong>：两次输入的密码必须一致!<br />';
     
	if($error == '') {
			$user_id = wp_create_user( $sanitized_user_login, $_POST['user_pass'], $user_email );
	   
		if ( ! $user_id ) {
			$error .= sprintf( '<strong>错误</strong>：无法完成您的注册请求... 请联系<a href=\"mailto:%s\">管理员</a>！<br />', get_option( 'admin_email' ) );
		}
		else if (!is_user_logged_in()) {
			$user = get_userdatabylogin($sanitized_user_login);
			$user_id = $user->ID;
	 
	      // 自动登录
			wp_set_current_user($user_id, $user_login);
			wp_set_auth_cookie($user_id);
			do_action('wp_login', $user_login);
		}
	}
}
?>
<?php get_header(); ?>
<article id="primary" class="site-content">
	<section class="single-content">
		<?php while ( have_posts() ) : the_post(); ?>
		<h1 class="reg-title" style="text-align: center;line-height: 40px; ">用户注册</h1> 
		<?php the_content(); ?>
		<div class="reg-page">
			<?php if(!empty($error)) {
	 			echo '<p class="user_error">'.$error.'</p>';
				}
			if (!is_user_logged_in()) { ?>
				<form name="registerform" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" class="user_reg">
				    <p>
				      <label for="user_login">用户名<br />
				        <input type="text" name="user_login" tabindex="1" id="user_login" class="input" value="<?php if(!empty($sanitized_user_login)) echo $sanitized_user_login; ?>" size="30" />
				      </label>
				    </p>

				    <p>
				      <label for="user_email">电子邮件<br />
				        <input type="text" name="user_email" tabindex="2" id="user_email" class="input" value="<?php if(!empty($user_email)) echo $user_email; ?>" size="30" />
				      </label>
				    </p>
				   
				    <p>
				      <label for="user_pwd1">密码(至少6位)<br />
				        <input id="user_pwd1" class="input" tabindex="3" type="password" tabindex="21" size="30" value="" name="user_pass" />
				      </label>
				    </p>
				   
				    <p>
				      <label for="user_pwd2">重复密码<br />
				        <input id="user_pwd2" class="input" tabindex="4" type="password" tabindex="21" size="30" value="" name="user_pass2" />
				      </label>
				    </p>
				   
				    <p class="submit">
				      <input type="hidden" name="user_reg" value="ok" />
				      <button class="button button-primary button-large" type="submit">注册</button>
				    </p>
				</form>
			<?php } else { ?>
				<p class="user_is">
					<strong><?php echo $user_identity; ?>&nbsp;&nbsp;您已是本站会员，并已登录</strong><br/>
			         <a href="<?php echo wp_logout_url( get_bloginfo('url') ); ?>" title="">退出</a>
					<?php
					    if (current_user_can('manage_options')) {
					        echo '&nbsp;&nbsp;<a href="' . admin_url() . '">管理站点</a>';
					    } else {
					        echo '&nbsp;&nbsp;<a href="' . admin_url() . 'profile.php">编辑个人资料</a>';
					    }
					?>
				</p>
			<?php } ?>
		</div>
	</section>
	<?php endwhile; ?>
</article>
<!-- #primary -->
<style type="text/css">
.user_reg {
	padding: 20px;
}
.reg-page p {
	text-indent: 0em;
}
p.user_error {
	background: #ffebe8;
	margin: 16px 0;
	padding: 12px;
	border: 1px solid #c00;
}
p.user_is {
	background: #fff;
	width: 45%;
	line-height: 40px; 
	text-align: center;
	margin: 30px auto;
	padding: 12px;
	border: 1px solid #ccc;
}
.user_reg label {
	cursor: pointer;
}
.user_reg .input {
	background: #fff;
	margin: 5px 0;
	padding: 5px;
	border: 1px solid #ccc;
	border-radius: 2px;
	-webkit-appearance: none;
}
.user_reg button {
	background: #04a4cc;
	width: 87px;
	height: 35px;
	color: #fff;
	text-align: center;
	margin: 10px 0 0 0;
	border: 0px;
	cursor: pointer;
	border-radius: 2px;
	-webkit-appearance: none;
}
</style>
<?php get_sidebar(); ?>
<?php get_footer(); ?> 