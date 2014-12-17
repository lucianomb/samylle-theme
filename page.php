<?php
/**
 * The page template file
 *
 * @package Samylle Aguiar
 * @since 0.1.0
 */
if( ! $_GET["ajax"] ) {
	get_header(); 
	$display = "";
} else {
	$display = 'style="opacity:0;"';
}
?>
<?php while ( have_posts() ) : the_post(); ?>
<section class="wrapper980 main page" data-category="shop" <?=$display?>>
	<article class="post">
		<h1><?=get_the_title()?></h1>
		<hr class="split">
		<? the_content() ?>
	</article>
	<? get_sidebar(); ?>
	<script>var exec = 0;</script>
	<img src="<?=get_template_directory_uri()?>/images/loader.gif" alt="" onload="samylle.scripts.page(exec++);">
</section>
<? endwhile; ?>
<?
if( ! $_GET["ajax"] ) {
	get_footer();
}
?>