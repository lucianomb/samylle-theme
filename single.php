<?php
/**
 * The single post template file
 *
 * @package Samylle Aguiar
 * @since 0.1.0
 */
if( ! $_GET["ajax"] ) {
	get_header(); 
	$display = "";
} else {
	$display = ' style="opacity:0;"';
}

while( have_posts() ) : the_post();

//Track views
set_post_views(get_the_ID());
?>
<section class="wrapper980 main page" data-category="<?=find_section(get_the_category(), "page");?>"<?=$display?>>
	<article class="post">
		<header>
			<h1><? the_title(); ?></h1>
			<hr class="split">
			<div class="mini-toolbar">
				<span class="info">by <strong><? the_author_nickname() ?></strong> em <time datetime="<?=get_the_date('Y-m-d H:i');?>"><?=strftime("%d de %B de %Y", get_the_date("U"))?></time></span>
				<div class="share" style="display:none;">
					<!-- Twitter -->
					<div class="social twitter">
						<iframe allowtransparency="true" frameborder="0" scrolling="no" src="http://platform.twitter.com/widgets/tweet_button.html?count=horizontal&url=<?=urlencode(get_permalink())?>" style="float: left; width: 120px; height:20px; background: transparent;"></iframe>
					</div>
					<!-- Facebook -->
					<div class="social facebook">
						<iframe src="//www.facebook.com/plugins/like.php?href=<?=urlencode(get_permalink())?>&amp;width&amp;height=21&amp;colorscheme=light&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;send=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; float: left; height:21px; background: transparent;" allowTransparency="true"></iframe>
					</div>
					<!-- Google Plus -->
					<div class="social google">
						<div class="g-plusone" data-size="medium"></div>
					</div>	
				</div>
			</div>
		</header>
		<? the_content(); ?>
		<footer>
			<section class="author">
				<h2><strong>Quem escreve</strong></h2>
				<img class="profile-picture" src="<?=get_template_directory_uri()?>/images/profile.jpg" alt="Samylle Aguiar">
				<div class="profile-tools">
					<h3><? the_author_nickname() ?></h3>
					<div class="social">
						<ul>
							<li><a class="sprite twitter" href="https://twitter.com/samylle_nosf" title="Twitter">Twitter</a></li>
							<li><a class="sprite facebook" href="http://facebook.com/samylle.aguiar" title="Facebook">Facebook</a></li>
							<li><a class="sprite instagram" href="http://instagram.com/samylle_nosf/" title="Instagram">Instagram</a></li>
						</ul>
					</div>
				</div>
				<p><? the_author_description(); ?></p>
			</section>
			<?
			$related = get_related_tag_posts_ids( $post->ID );
			endwhile;
			if( $related ) :
				$args = array(
					'post__in'      => $related,
					'orderby'       => 'post__in',
					'no_found_rows' => true
				);
				$related_posts = get_posts( $args );
				if ( $related_posts ) :
			?>
			<section class="related">
				<h2 class="alternate"><strong>Relacionados</strong></h2>
				<ul>
					<?
					foreach ( $related_posts as $post ) :
					?>
					<li><a href="<?=get_permalink(); ?>"><?the_post_thumbnail( 'related' );?> <?='<strong>' . get_the_title() . "</strong> " . substr( get_the_excerpt(), 0, strrpos( substr( get_the_excerpt(), 0, 80), ' ' ) ) . '...'; ?></a></li>
					<?
					endforeach;
					?>
				</ul>
			</section>
			<?
					wp_reset_postdata();
				endif;
			endif;
			?>
			<hr class="split">
			<?php comments_template(); ?>
		</footer>
	</article>
	<? get_sidebar(); ?>
	<script>var exec = 0;</script>
	<img src="<?=get_template_directory_uri()?>/images/loader.gif" alt="" onload="samylle.scripts.post(exec++);">
</section>
<?
if( ! $_GET["ajax"] ) {
	get_footer();
}
?>