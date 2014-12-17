<?php
/**
 * The main template file
 *
 * @package Samylle Aguiar
 * @since 0.1.0
 */
if( ! $_GET["ajax"] && ! $_GET["loadmore"] ) {
	get_header(); 
	$display = "";
	$step = 1;
} else {
	$display = 'style="opacity:0;"';
	$step = isset( $_GET["page"] ) ? $_GET["page"] : 1;
	$hidden = ( $_GET["loadmore"] ) ? 'generated' : '';
}
$banner = array();
?>
<? if( ! $_GET["loadmore"] ) : ?>
<section class="wrapper980 main home" <?=$display?>>
	<ul class="posts">
		<li class="block-2x1">
			<ul class="slider">
			<? endif; ?>
			<?
			query_posts( "posts_per_page=5&meta_key=slider&meta_value=1" );
			while ( have_posts() ) : the_post();
			array_push( $banner , get_the_ID() );
			?>
			<? if( ! $_GET["loadmore"] ) : ?>
			<li><a href="<?=get_permalink()?>">
				<header class="<?=colorize();?>">
					<time datetime="<?=get_the_date('Y-m-d');?>"><?=strftime("%d %b, %Y", get_the_date("U"))?> <?=show_icon(get_field('content'));?></time>
					<h3><span><?=find_section(get_the_category(), "post");?><? the_title(); ?></span></h3>
				</header>
				<? the_post_thumbnail( 'home-wide' );?>
			</a></li>
		<? endif; ?>
		<?
		endwhile;
		wp_reset_query();
		?>
		<? if( ! $_GET["loadmore"] ) : ?>
	</ul>
	<ul class="slider-control">
		<li><div class="sprite prev" data-dir="prev">Anterior</div></li>
		<li><div class="sprite next" data-dir="next">Próximo</div></li>
	</ul>
</li>
<? endif; ?>
<?	
$args = array(
	'posts_per_page' => 2,
	'post_type' => 'ads',
	'paged' => $step,
	'meta_key' => 'order',
	'orderby' => 'meta_value_num',
	'order' => 'asc'
	);
$query = new WP_Query( $args );
$ads = $query->posts;
$ads_loaded = count( $ads );
wp_reset_postdata();

$ad_position = -1;
set_post_views(get_the_ID());
?>
<? if( ! $_GET["loadmore"] ) : $post = get_post( $ads[++$ad_position] ); ?>
<li class="block-1x1 last <?=$hidden;?>">
	<a href="<?=get_field('url')?>"><?=wp_get_attachment_image( get_field('imagem'), 'home-square', false, 'alt=Publicidade' )?></a>
</li>
<? endif; ?>
<?	
$args = array(
	'posts_per_page' => ( $ads_loaded > 1 ) ? 6 : 7,
	'post_type' => 'post',
	'paged' => $step,
	'post__not_in' => $banner
	);
$query = new WP_Query( $args );
$pack = $query->posts;
$posts_loaded = count($pack);
wp_reset_postdata();
$end_post = '<li class="block-1x1 last end"><span>Fim dos posts!</span></li><script>samylle.infiniteScroll.stop = true; $(".end").parent().parent().css("overflow", "visible"); $(".loading.home-loading").fadeOut();</script>';
$more = true; $printed = false;
?>
<li class="side-1x2">
	<ul>
		<?
		if( $posts_loaded > 0 ) :
			$post = get_post( $pack[0] );
		?>
		<li class="block-1x1 <?=$hidden;?>"><a href="<?=get_permalink()?>">
			<header class="<?=colorize();?>">
				<time datetime="<?=get_the_date('Y-m-d');?>"><?=strftime("%d %b, %Y", get_the_date("U"))?> <?=show_icon(get_field('content'));?></time>
				<h3><span><?=find_section(get_the_category(), "post");?><? the_title(); ?></span></h3>
			</header>
			<?
			if( get_the_post_thumbnail() != "" ) {
				the_post_thumbnail( 'home-square' );
			} else {
				echo '<div class="img-container"><img src="' . get_first_image() . '" class="manual"></div>';
				echo '<script>$(".img-container img").last().css("height",($(this).height() < $(this).width()) ? "320px" : "auto").css("width",($(this).height() > $(this).width()) ? "320px" : "auto");</script>';
			}
			?>
		</a></li>
		<?
		elseif( ! $printed ) : print $end_post; $more = false; $printed = true; endif;

		if( $posts_loaded > 1 && $more ) :
			$post = get_post( $pack[1] );
				$post_position = 2; //setup post position equal 2 cause the next numbers will be defined by the existence of the ad
				?>
				<li class="block-1x1 <?=$hidden;?>"><a href="<?=get_permalink()?>">
					<header class="<?=colorize();?>">
						<time datetime="<?=get_the_date('Y-m-d');?>"><?=strftime("%d %b, %Y", get_the_date("U"))?> <?=show_icon(get_field('content'));?></time>
						<h3><span><?=find_section(get_the_category(), "post");?><? the_title(); ?></span></h3>
					</header>
					<?
					if( get_the_post_thumbnail() != "" ) {
						the_post_thumbnail( 'home-square' );
					} else {
						echo '<div class="img-container"><img src="' . get_first_image() . '" class="manual"></div>';
						echo '<script>$(".img-container img").last().css("height",($(this).height() < $(this).width()) ? "320px" : "auto").css("width",($(this).height() > $(this).width()) ? "320px" : "auto");</script>';
					}
					?>
				</a></li>
				<?
				elseif( ! $printed ) : print $end_post; $more = false; $printed = true; endif;				
				?>
			</ul>
		</li>
		<?
		if( $posts_loaded >= 2 && $more ) :
			$post = get_post( $pack[2] );
		setup_postdata( $post );
		?>
		<li class="block-2x2 last <?=$hidden;?>">
			<a class="featured" href="<?=get_permalink()?>">
				<header class="<?=colorize();?>">
					<time datetime="<?=get_the_date('Y-m-d');?>"><?=strftime("%d %b, %Y", get_the_date("U"))?> <?=show_icon(get_field('content'));?></time>
				</header>
				<?
				if( get_the_post_thumbnail() != "" ) {
					echo '<div class="img-container">';
					the_post_thumbnail( 'home-wide' );
					echo '</div>';
				} else {
					echo '<div class="img-container"><img src="' . get_first_image() . '" class="manual"></div>';
					echo '<script>$(".img-container img").last().css("height",($(this).height() < $(this).width()) ? "320px" : "auto").css("width",($(this).height() > $(this).width()) ? "650px" : "auto");</script>';
				}
				?>
			</a>
			<article>
				<h2><? the_title(); ?></h2>
				<? the_excerpt(2172) ?>
				<ul class="info">
					<li class="sprite views"><?=get_post_views(get_the_ID())?></li>
					<li class="sprite comments"><a class="comment-count" href="<?=get_permalink()?>#disqus_thread">comentários</a></li>
					<li class="sprite more"><a href="<?=get_permalink()?>">Saber mais</a></li>
				</ul>
			</article>
		</li>
		<?
		elseif( ! $printed ) : print $end_post; $more = false; $printed = true; endif;				
		if( $ads_loaded > 1 ) :
			$post = get_post( $ads[++$ad_position] );
		set_post_views(get_the_ID());
		?>
		<li class="block-1x1 <?=$hidden;?>">
			<a href="<?=get_field('url')?>"><?=wp_get_attachment_image( get_field('imagem'), 'home-square', false, 'alt=Publicidade' )?></a>
		</li>
		<?
		elseif( $posts_loaded > $post_position && $more ) :
			$post = get_post( $pack[++$post_position] );
		?>
		<li class="block-1x1 <?=$hidden;?>"><a href="<?=get_permalink()?>">
			<header class="<?=colorize();?>">
				<time datetime="<?=get_the_date('Y-m-d');?>"><?=strftime("%d %b, %Y", get_the_date("U"))?> <?=show_icon(get_field('content'));?></time>
				<h3><span><?=find_section(get_the_category(), "post");?><? the_title(); ?></span></h3>
			</header>
			<?
			if( get_the_post_thumbnail() != "" ) {
				the_post_thumbnail( 'home-square' );
			} else {
				echo '<div class="img-container"><img src="' . get_first_image() . '" class="manual"></div>';
				echo '<script>$(".img-container img").last().css("height",($(this).height() < $(this).width()) ? "320px" : "auto").css("width",($(this).height() > $(this).width()) ? "320px" : "auto");</script>';
			}
			?>
		</a></li>
		<?
		elseif( ! $printed ) : print $end_post; $more = false; $printed = true; endif;
		if( ! $printed ) :
			$section1 = sectionize();
		?>
		<li class="block-1x1 featured-section red <?=$hidden;?>"><a href="<?=get_category_link( $section1 );?>">
			<h4 class="with-arrow"><?=get_cat_name( $section1 );?></h4>
			<div class="sprite arrow"></div>
		</a></li>
		<?
		endif;
		if( $posts_loaded > $post_position && $more ) :
			$post = get_post( $pack[++$post_position] );
		?>
		<li class="block-1x1 last <?=$hidden;?>"><a href="<?=get_permalink()?>">
			<header class="<?=colorize();?>">
				<time datetime="<?=get_the_date('Y-m-d');?>"><?=strftime("%d %b, %Y", get_the_date("U"))?> <?=show_icon(get_field('content'));?></time>
				<h3><span><?=find_section(get_the_category(), "post");?><? the_title(); ?></span></h3>
			</header>
			<?
			if( get_the_post_thumbnail() != "" ) {
				the_post_thumbnail( 'home-square' );
			} else {
				echo '<div class="img-container"><img src="' . get_first_image() . '" class="manual"></div>';
				echo '<script>$(".img-container img").last().css("height",($(this).height() < $(this).width()) ? "320px" : "auto").css("width",($(this).height() > $(this).width()) ? "320px" : "auto");</script>';
			}
			?>
		</a></li>
		<?
		elseif( ! $printed ) : print $end_post; $more = false; $printed = true; endif;				
		if( $step == 1 || $ads_loaded < 1 && ! $printed ) : //if we are in the first page or we are in the others but there are no more ads to show
		$section2 = sectionize();
		while( $section2 == $section1 ) {
			$section2 = sectionize();
		}
		?>
		<li class="block-1x1 featured-section <?=$hidden;?>"><a href="<?=get_category_link( $section2 );?>">
			<h4><?=get_cat_name( $section2 );?></h4>
		</a></li>
		<?
		elseif( $more ) :
			$post = get_post( $ads[++$ad_position] );
		set_post_views(get_the_ID());
		?>
		<li class="block-1x1 <?=$hidden;?>">
			<a href="<?=get_field('url')?>"><?=wp_get_attachment_image( get_field('imagem'), 'home-square', false, 'alt=Publicidade' )?></a>
		</li>
		<?
		endif;

		if( $posts_loaded > $post_position && $more ) :
			$post = get_post( $pack[++$post_position] );
		?>
		<li class="block-1x1 <?=$hidden;?>"><a href="<?=get_permalink()?>">
			<header class="<?=colorize();?>">
				<time datetime="<?=get_the_date('Y-m-d');?>"><?=strftime("%d %b, %Y", get_the_date("U"))?> <?=show_icon(get_field('content'));?></time>
				<h3><span><?=find_section(get_the_category(), "post");?><? the_title(); ?></span></h3>
			</header>
			<?
			if( get_the_post_thumbnail() != "" ) {
				the_post_thumbnail( 'home-square' );
			} else {
				echo '<div class="img-container"><img src="' . get_first_image() . '" class="manual"></div>';
				echo '<script>$(".img-container img").last().css("height",($(this).height() < $(this).width()) ? "320px" : "auto").css("width",($(this).height() > $(this).width()) ? "320px" : "auto");</script>';
			}
			?>
		</a></li>
		<?
		elseif( ! $printed ) : print $end_post; $more = false; $printed = true; endif;				
		if( $posts_loaded > $post_position && $more ) :
			$post = get_post( $pack[++$post_position] );
		?>
		<li class="block-1x1 last <?=$hidden;?>"><a href="<?=get_permalink()?>">
			<header class="<?=colorize();?>">
				<time datetime="<?=get_the_date('Y-m-d');?>"><?=strftime("%d %b, %Y", get_the_date("U"))?> <?=show_icon(get_field('content'));?></time>
				<h3><span><?=find_section(get_the_category(), "post");?><? the_title(); ?></span></h3>
			</header>
			<?
			if( get_the_post_thumbnail() != "" ) {
				the_post_thumbnail( 'home-square' );
			} else {
				echo '<div class="img-container"><img src="' . get_first_image() . '" class="manual"></div>';
				echo '<script>$(".img-container img").last().css("height",($(this).height() < $(this).width()) ? "320px" : "auto").css("width",($(this).height() > $(this).width()) ? "320px" : "auto");</script>';
			}
			?>
		</a></li>
	<? elseif( ! $printed ) : print $end_post; endif; ?>
	<? if( ! $_GET["loadmore"] ) : ?>
</ul>
<script>var exec = 0 ;</script>
<img src="<?=get_template_directory_uri()?>/images/loader.gif" alt="" onload="samylle.scripts.home(exec++);">
<div class="loading home-loading"></div>
</section>
<?
endif;
if( ! $_GET["ajax"] && ! $_GET["loadmore"] ) {
	get_footer();
}
?>