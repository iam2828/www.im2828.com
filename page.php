<?php get_header(); ?>
<div id="primary" class="site-content">
	<?php while ( have_posts() ) : the_post(); ?>
	<section class="single-content">
		<?php get_template_part( 'content' ); ?>
		<?php get_template_part( 'inc/social' ); ?>
	</section>
	<!-- #content -->
	<div class="authorbio">
		<?php get_template_part( 'inc/copyright' ); ?>
	</div>
	<?php comments_template( '', true ); ?>
	<?php endwhile; ?>
</div>
<!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>