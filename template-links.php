<?php
/*
Template Name: 友情链接
*/
?>
<?php get_header(); ?>
<div id="link-page">
	<?php while ( have_posts() ) : the_post(); ?>
	<section class="link-content">
		<?php
			$bookmarks = get_bookmarks();
			if ( !empty($bookmarks) ){
			    echo '<ul class="link-site">';
			    foreach ($bookmarks as $bookmark) {
			        echo '<li><a href="' . $bookmark->link_url . '" title="' . $bookmark->link_description . '" target="_blank" >'. get_avatar($bookmark->link_notes,128) . '<span class="link-name">'. $bookmark->link_name .'</span></a></li>';
			    }
			    echo '</ul>';
			}
		?>
		<div class="clear"></div>
	</section>
	<?php endwhile; ?>
</div>
<div class="clear"></div>
<?php get_footer(); ?>
<style type="text/css">
#link-page {
	float: left;
	width: 100%;
	margin: 10px 0;
}
.link-content {
	width: 100%;
	padding: 0 0 0 5px;
	overflow: hidden;
}
.link-site li {
	float: left;
	width: 142px;
	text-align: center;
	margin: 0 10px 20px 0;
	border: 1px solid #f1f1f1;
	border-radius: 2px;
}
.link-site li img{
	width: 64px;
	height: 64px;
	margin: 20px 0;
	transition: 0.5s;
	-webkit-transtion: 0.5s
}
.link-name {
	background: #04a4cc;
	color: #fff;
	line-height: 40px;
}
.link-site li span {
	display: block
}
.link-site li:hover img{
	-webkit-transform: scale(1.5);
	-moz-transform: scale(1.5);
	-ms-transform: scale(1.5);
	-o-transform: scale(1.5);
}
@media screen and (max-width: 1080px) {
	.link-site li {
		margin: 0 28px 20px 0;
	}
}
</style>