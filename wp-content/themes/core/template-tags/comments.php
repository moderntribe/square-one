<?php

/**
 * Template Tags: Comments Output
 */


/**
 * Customize comment output
 */
if ( ! function_exists( 'core_comment' ) ) :

	function core_comment( $comment, $args, $depth ) {

		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :

			case 'pingback': ?>

				<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'post-interaction' ); ?>>
					<p>
						<strong>Pingback:</strong>
						<?php comment_author_link(); ?>
						<?php edit_comment_link( '(Edit)', '<span class="comment__action-edit">', '</span>' ); ?>
					</p>

				<?php break;

			case 'trackback': ?>

				<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'post-interaction' ); ?>>
				<p>
					<strong>Trackback:</strong>
					<?php comment_author_link(); ?>
					<?php edit_comment_link( '(Edit)', '<span class="comment__action-edit">', '</span>' ); ?>
				</p>

				<?php break;

			default: ?>

				<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'post-interaction' ); ?>>

					<header class="comment__header">

						<?php // Gravatar
						echo get_avatar( $comment, 150 ); ?>

						<h5 class="comment__title" rel="author">
							<cite><?php echo get_comment_author(); ?></cite>
						</h5>

						<time class="comment__time" datetime="<?php echo get_comment_time( 'c' ); ?>">
							<?php echo get_comment_time( 'g:i A - M j, Y' ); ?>
						</time>

					</header><!-- .comment-header -->

					<div class="comment__text">

						<?php edit_comment_link( 'Edit Comment', '<p class="comment__action-edit">', '</p>' ); ?>

						<?php comment_text(); ?>

					</div><!-- .comment-text -->

					<?php // Moderation text
					if ( $comment->comment_approved == '0' ) { ?>
						<p class="comment__message-moderation">Your comment is awaiting moderation.</p>
					<?php } ?>

					<?php // Reply
					comment_reply_link(
						array_merge( $args, array(
							'reply_text' => 'Reply',
							'before'	 => '<p class="comment__action-reply">',
							'after' 	 => '</p>',
							'depth' 	 => $depth,
							'max_depth'  => $args['max_depth']
						) ) ); ?>

			<?php break;

		endswitch;

	}

endif;
