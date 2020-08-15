<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\comments\comments_section;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\comments\comment\Comment_Controller;
use Tribe\Project\Templates\Components\comments\trackback\Trackback_Controller;
use Tribe\Project\Templates\Components\pagination\comments\Comments_Pagination_Controller;

class Comments_Section_Controller extends Abstract_Controller {

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
			Comments_Pagination_Controller::PAGED => $paged,
		] );
	}

	/**
	 * Renders a comment and echos it. This is the callback
	 * for wp_list_comments(), and is expected to be called
	 * in the context of an output buffer.
	 *
	 * @param \WP_Comment $comment
	 * @param array       $args
	 * @param int         $depth
	 *
	 * @return void
	 */
	public function render_comment( \WP_Comment $comment, array $args, int $depth ): void {
		if ( in_array( $comment->comment_type, [ 'pingback', 'trackback' ], true ) ) {
			$this->render_trackback_comment( $comment );
		} else {
			$this->render_regular_comment( $comment, $args, $depth );
		}
	}

	private function render_trackback_comment( \WP_Comment $comment ): void {
		$label = $comment->comment_type === 'pingback' ? __( 'Pingback:', 'tribe' ) : __( 'Trackback:', 'tribe' );

		get_template_part( 'components/comments/trackback/trackback', null, [
			Trackback_Controller::COMMENT_ID => $comment->comment_ID,
			Trackback_Controller::LABEL      => $label,
		] );
	}

	private function render_regular_comment( \WP_Comment $comment, array $args, int $depth ): void {
		get_template_part( 'components/comments/comment/comment', null, [
			Comment_Controller::COMMENT_ID => $comment->comment_ID,
			Comment_Controller::DEPTH      => $depth,
			Comment_Controller::MAX_DEPTH  => $args['max_depth'],
		] );
	}
}
