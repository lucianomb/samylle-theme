<?php
/**
 * The template for displaying the header.
 *
 * @package Samylle Aguiar
 * @since 0.1.0
 */
if( ! is_user_logged_in() ) {
wp_redirect(wp_login_url(home_url()));
}
?><!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<? if( is_single() ) :
get_posts();
foreach( $posts as $post ) :
	setup_postdata( $post );
?>
<meta name="description" content="<?=(is_single() && get_the_excerpt() != "") ? substr( get_the_excerpt(), 0, strrpos( substr( get_the_excerpt(), 0, 160), ' ' ) ) . "..." : 'Blog da Samylle - Aqui você encontra tudo sobre moda, estilo de vida e muito mais!' ?>" />
<?
endforeach;
else:
?>
<meta name="description" content="Blog da Samylle - Aqui você encontra tudo sobre moda, estilo de vida e muito mais!" />
<?
endif;
?>
<title><?php wp_title( '|', true, 'right' ); ?></title>
<?
wp_head() ;
?>
<script src="<?=get_template_directory_uri()?>/assets/js/jquery.min.js"></script>
<script src="<?=get_template_directory_uri()?>/assets/js/jquery.history.js"></script>
<script src="<?=get_template_directory_uri()?>/assets/js/samylle_aguiar.js"></script>

<!-- G+ -->
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
  {lang: 'pt-BR', parsetags: 'explicit'}
</script>
</head>
<body>
<div id="fb-root"></div>
<script>
	//facebook
	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>

<header class="samylle">
	<div class="video no-support">
		<video loop autoplay preload>
			<source src="<?=get_template_directory_uri()?>/videos/header.webm" type='video/webm; codecs="vp8, vorbis"' />
			<source src="<?=get_template_directory_uri()?>/videos/header.mp4" type='video/mp4' />
		</video>
	</div>
	<div class="wrapper980">
		<a class="logo" href="<?=get_site_url();?>">Samylle Aguiar</a>
		<nav class="menu">
			<div class="hover"></div>
			<ul>
				<? /*<li class="login">
					<a class="sprite" href="#login">@</a>
					<div class="login-box">
						<form role="login" method="get" id="loginform" class="loginform" action="<?=get_site_url()?>">
							<div>
								<input type="text" placeholder="Usuário" name="user" id="user">
								<input type="text" placeholder="Senha" name="pass" id="pass">
								<input type="submit" id="loginsubmit" value="Login">
								<a href="#">Esqueci a senha</a>
							</div>
						</form>
					</div>
				</li>*/?>
				<li class="sa"><a href="<?=get_site_url();?>">SA.</a></li>
				<li class="life-style">
					<a href="<?=get_site_url();?>/category/life-style/">Life Style</a>
					<?/* <ul>
						<li><a href="#">Sublink 1</a></li>
						<li><a href="#">Sublink 2</a></li>
					</ul> */?>
				</li>
				<li class="crazy-world"><a href="<?=get_site_url();?>/category/crazy-world/">Crazy World</a></li>
				<li class="videos"><a href="<?=get_site_url();?>/category/videos/">Videos</a></li>
				<li class="contact"><a href="<?=get_site_url();?>/contato">Contact</a></li>
				<li class="shop"><a href="<?=get_site_url();?>/shop">Shop</a></li>
			</ul>
			<? get_search_form() ;?>
		</nav>
	</div>
</header>
	
