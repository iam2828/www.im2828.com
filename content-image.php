<!-- 图像 -->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<section class="content" onMouseOut="this.className='content'" onMouseOver="this.className='content-im'">
		<span class="new-ico">PHOTO</span>
		<div class="entry-content-img">
			<figure class="image-format">
				<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>" ><?php all_img($post->post_content);?><div class="clear"></div></a>
			</figure>
			<div class="clear"></div>
		</div>
	</section>
</article>