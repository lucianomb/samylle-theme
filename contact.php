<?php
/**
 * Template Name: Página de Contato
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
<section class="wrapper980 main page" data-category="contact" <?=$display?>>
	<article class="post">
		<h1>Contato</h1>
		<hr class="split">
		<div class="contact-form">
			<form>
				<input required type="text" id="name" placeholder="Nome">
				<input required type="email" id="email" placeholder="E-mail">
				<textarea required name="text" id="text" placeholder="Escreva aqui sua mensagem"></textarea>
				<input type="submit" id="submit" value="Enviar">
			</form>
		</div>
	</article>
	<? get_sidebar(); ?>
	<script>var exec = 0;</script>
	<img src="<?=get_template_directory_uri()?>/images/loader.gif" alt="" onload="samylle.scripts.contact(exec++);">
</section>
<? endwhile; ?>
<?
if( ! $_GET["ajax"] ) {
	get_footer();
}
?>