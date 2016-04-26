<?php
/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

		<h6 class="comments-title">
			<?php
			printf( 
				_nx( '1 Response to &ldquo;%2$s&rdquo;', '%1$s Responses to &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title' ),
				number_format_i18n( get_comments_number() ), '<a href="'. get_permalink() .'" rel="bookmark">' . get_the_title() . '</a>' );
			?>
		</h6>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'callback'   => 'core_comment',
					'style'      => 'ol',
					'short_ping' => true
				) );
			?>
		</ol><!-- .comment-list -->

		<?php // Content: Pagination
		get_template_part( 'content/pagination/comments' ); ?>

		<?php if ( ! comments_open() ) { ?>
			<p class="no-comments">Comments are closed.</p>
		<?php } ?>

	<?php endif; // have_comments() ?>

	<?php comment_form(); ?>

</div><!-- #comments -->
