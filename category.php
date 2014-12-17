<?php
/**
 * The main template file
 *
 * @package Samylle Aguiar
 * @since 0.1.0
 */
if( ! $_GET["ajax"] ) {
	get_header(); 
	$display = "";
	$paged = 2;
} else {
	$display = 'style="opacity:0;"';
	$paged = isset( $_POST["paged"] ) ? $_POST["paged"] : 2;
}
$category = get_category( get_query_var( "cat" ) );
$slug = $category->slug;
?>
<section class="wrapper980 main home" data-category="<?=$slug;?>" <?=$display?>>
	<h1><?=single_cat_title( '', false )?></h1>
	<hr class="split">
	<? if ( have_posts() ) : ?>
	<ul class="posts">
		<?php while ( have_posts() ) : the_post(); ?>
			<li class="block-1x1"><a href="<?=get_permalink()?>">
				<header class="<?=colorize();?>">
					<time datetime="<?=get_the_date('Y-m-d');?>"><?=strftime("%d %b, %Y", get_the_date("U"))?> <?=show_icon(get_field('content'));?></time>
					<h3><span><?=find_section(get_the_category(), "post");?><? the_title(); ?></span></h3>
				</header>
				<? the_post_thumbnail( 'home-square' );?>
			</a></li>
		<? endwhile; ?>
	</ul>
	<? else : ?>
	<p><strong>Nenhum post publicado nesta seção ainda!</strong></p>
	<? endif; ?>
</section>
<?
if( ! $_GET["ajax"] ) {
	get_footer();
}
?>