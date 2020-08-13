<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\comments\comments_section;

use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {

	public function get_title() {
		return sprintf(
			_nx(
				__( '%1$s Comment', 'tribe' ),
				__( '%1$s Comments', 'tribe' ),
				get_comments_number(),
				'comments title'
			),
			number_format_i18n( get_comments_number() )
		);
	}

	public function get_comments() {
		return wp_list_comments( [
			'callback'   => [ $this, 'render_comment' ],
			'style'      => 'ol',
			'short_ping' => true,
			'echo'       => false,
		] );
	}

	public function get_comment_form() {
		$user          = wp_get_current_user();
		$user_identity = $user->exists() ? $user->display_name : '';
		$logged_in_as  = sprintf( esc_html__( 'Logged in as %s', 'tribe' ), $user_identity );

		ob_start();

		comment_form( [
			'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title h4">',
			'class_form'         => 'comment-form s-sink t-sink',
			'logged_in_as'       => sprintf( '<p class="logged-in-as">%s</p>', $logged_in_as ),
			'class_submit'       => 'submit c-btn',
		] );

		return ob_get_clean();
	}

	public function get_pagination() {
		$count = get_comment_pages_count();
		$paged = (bool) ( $count > 1 ? get_option( 'page_comments' ) : false );

		return tribe_template_part( 'components/pagination/comments/comments', null, [
			'paged' => $paged,
		] );
	}

	/**
	 * Renders a comment and echos it. This is the callback
	 * for wp_list_comment(), and is expected to be called
	 * in the context of an output buffer.
	 *
	 * @param \WP_Comment $comment
	 * @param array       $args
	 * @param int         $depth
	 *
	 * @return string
	 */
	public function render_comment( $comment, $args, $depth ): void {
		$classes = get_comment_class( [], $comment, $comment->comment_post_ID );

		if ( in_array( $comment->comment_type, [ 'pingback', 'trackback' ], true ) ) {
			$label = $comment->comment_type === 'pingback' ? __( 'Pingback:', 'tribe' ) : __( 'Trackback:', 'tribe' );
			$edit  = $this->comment_edit_link( $comment->comment_ID, __( '(Edit)', 'tribe' ) );

			get_template_part( 'components/comments/trackback/trackback', null, [
				'comment_id' => $comment->comment_ID,
				'label'      => $label,
				'edit_link'  => $edit,
				'classes'    => $classes,
			] );
		}

		get_template_part( 'components/comments/comment/comment', null, [
			'comment_id' => $comment->comment_ID,
			'edit_link'  => $this->comment_edit_link( $comment->comment_ID, __( 'Edit Comment', 'tribe' ) ),
			'classes'    => $classes,
			'reply_link' => get_comment_reply_link( array_merge( $args, [
				'reply_text' => __( 'Reply', 'tribe' ),
				'depth'      => $depth,
				'max_depth'  => $args[ 'max_depth' ],
			] ) ),
		] );
	}

	private function comment_edit_link( $comment_id, $text ): string {
		$url = get_edit_comment_link( $comment_id );
		if ( empty( $url ) ) {
			return '';
		}

		return tribe_template_part( 'components/link.link', null, [
			'classes' => [ 'comment-edit-link' ],
			'content' => $text,
			'url'     => $url,
		] );
	}
}
