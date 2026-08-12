<div id="searchbar">
	<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
		<div><input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="输入搜索内容" required />
		<input type="submit" id="searchsubmit" value="搜索" />
		</div>
	</form>
</div>