<div id="commentsection">

<?php
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	if ( post_password_required() ) { ?>
		<h3 id="comments"><?php _e('Comments','themolitor');?></h3>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password above to view or leave a comment.','themolitor');?></p>
<?php return; } ?>

<!--IF THERE ARE COMMENTS-->
<?php if ( have_comments() ) : ?>
	<h3 id="comments"><?php comments_number(__('No Comments','themolitor'), __('1 Comment','themolitor'), __('% Comments','themolitor') ); ?></h3>

	<ol class="commentlist">
	<?php wp_list_comments('avatar_size=90'); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
<?php 
endif;

$comments_args = array(
	'comment_notes_after' => '', // remove "Text or HTML to be displayed after the set of comment fields"
	'comment_field' => '<p><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>' // redefine your own textarea (the comment body)
);
comment_form($comments_args);
?> 
</div><!--end commentsection-->