<?php get_header(); ?>
<div id="primary" class="site-content">
	<?php while ( have_posts() ) : the_post(); ?>
	<div class="single-content">
		<?php get_template_part( 'content' ); ?>
		<?php get_template_part( 'inc/social' ); ?>
	</div>
	<div class="single-tag">标签：<?php the_tags('','',''); ?></div>
	<!-- #content -->
	<div class="authorbio">
		<?php get_template_part( 'inc/copyright' ); ?>
	</div>
	<?php if (get_option('cx_bdtj') == 'true') { ?>
		<?php if ( wp_is_mobile() ){
			echo '';
		    }else{
			get_template_part( 'inc/bdtj' );
		} ?>
	<?php } ?>

	<div id="single-widget">
		<?php dynamic_sidebar( 'sidebar-3' ); ?>
		<div class="clear"></div>
	</div>

	<nav class="nav-single">
		<?php previous_post_link('【上篇】%link') ?><br/><?php next_post_link('【下篇】%link') ?>
	</nav>
	<?php comments_template( '', true ); ?>
	<?php endwhile; ?>
</div>
<!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>