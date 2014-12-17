<aside class="sidebar">
	<ul class="archive">
		<li class="active" data-target="popular">Popular</li>
		<li data-target="recent">Mais Recentes</li>
	</ul>
	<ul class="articles">
		<?
		query_posts( "meta_key=wpb_post_views_count&orderby=meta_value_num&posts_per_page=4" );
		while ( have_posts() ) : the_post();
		?>
		<li><a href="<?=get_permalink();?>"><?
			the_post_thumbnail( 'sidebar' );
			echo "<p><strong>" . get_the_title() . "</strong> " . substr( get_the_excerpt(), 0, strrpos( substr( get_the_excerpt(), 0, 80), ' ' ) ) . "...</p>";
		?></a></li>
		<?
		endwhile;
		wp_reset_query();
		?>
		<li class="clear-both"></li>
	</ul>
</aside>