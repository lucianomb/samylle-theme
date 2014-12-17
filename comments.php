<p class="count-comments"><?=comments_number( 'Seja o(a) primeiro(a) a comentar', 'Um comentário', '% comentários' );?> sobre "<? the_title() ?>"</p>
<?php if ( have_comments() ) : ?>
<hr class="split">
<ol class="comment-list" style="display:none;">
	<?php wp_list_comments('type=comment'); ?>
</ol>
<? endif; ?>
<hr class="split">
<p class="write-comment">Escreva um comentário</p>
<hr class="split">
<form action="http://127.0.1.1/samylle/wp-comments-post.php" method="post" id="commentform" class="comment-form" novalidate="">
	<p class="comment-form-author"><input id="author" name="author" type="text" value="" size="30" placeholder="Nome"></p>
	<p class="comment-form-email"><input id="email" name="email" type="email" value="" size="30" placeholder="E-mail"></p>
	<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" placeholder="Comentário"></textarea></p>
	<p class="form-submit">
		<input name="submit" type="submit" id="submit" value="Publicar comentário">
		<input type="hidden" name="comment_post_ID" value="1" id="comment_post_ID">
		<input type="hidden" name="comment_parent" id="comment_parent" value="0">
	</p>
</form>