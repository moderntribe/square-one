<?php
/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments" data-js="comment-form">

	<?php if ( have_comments() ) : ?>

		<h6 class="comments__title">
			<?php
			printf( 
				_nx( __( '1 Response to &ldquo;%2$s&rdquo;', 'tribe' ), __( '%1$s Responses to &ldquo;%2$s&rdquo;', 'tribe' ), get_comments_number(), 'comments title' ),
				number_format_i18n( get_comments_number() ), '<a href="'. get_permalink() .'" rel="bookmark">' . get_the_title() . '</a>' );
			?>
		</h6>

		<ol class="comments__list">
			<?php
				wp_list_comments( array(
					'callback'   => 'core_comment',
					'style'      => 'ol',
					'short_ping' => true
				) );
			?>
		</ol>

		<?php // Content: Pagination
		get_template_part( 'content/pagination/comments' ); ?>

		<?php if ( ! comments_open() ) { ?>
			<p class="comments__none"><?php esc_html_e( 'Comments are closed.', 'tribe' ); ?></p>
		<?php } ?>

	<?php endif; ?>

	<?php comment_form(); ?>

</div>
