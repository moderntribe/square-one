<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers;

use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Components\Comments\Comment;
use Tribe\Project\Templates\Components\Comments\Comments_Section as Comments_Context;
use Tribe\Project\Templates\Components\Comments\Trackback;
use Tribe\Project\Templates\Components\Link;

class Comments extends Abstract_Controller {
	public function render( string $path = '' ): string {
		$password_required = post_password_required();
		$have_comments     = ( ! $password_required ) && have_comments();

		return $this->factory->get( Comments_Context::class, [
			Comments_Context::PASSWORD_REQUIRED => $password_required,
			Comments_Context::HAVE_COMMENTS     => $have_comments,
			Comments_Context::OPEN              => comments_open(),
			Comments_Context::TITLE             => $this->get_title(),
			Comments_Context::COMMENTS          => $have_comments ? $this->get_comments() : '',
			Comments_Context::FORM              => $this->get_comment_form(),
			Comments_Context::PAGINATION        => $this->get_pagination(),
		] )->render( $path );
	}

	protected function get_title() {
		return sprintf(
			_nx( __( '%1$s Comment', 'tribe' ), __( '%1$s Comments', 'tribe' ), get_comments_number(), 'comments title' ),
			number_format_i18n( get_comments_number() )
		);
	}

	protected function get_comments() {
		return wp_list_comments( [
			'callback'   => [ $this, 'render_comment' ],
			'style'      => 'ol',
			'short_ping' => true,
			'echo'       => false,
		] );
	}

	protected function get_comment_form() {
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

	protected function get_pagination() {
		$count = get_comment_pages_count();
		$paged = (bool) ( $count > 1 ? get_option( 'page_comments' ) : false );

		$data = [
			'paged'         => $paged,
			'max_num_pages' => $count,
			'previous'      => '',
			'next'          => '',
		];

		if ( $paged ) {
			$data['previous'] = get_previous_comments_link( __( '&larr; Older Comments', 'tribe' ) );
			$data['next']     = get_next_comments_link( __( 'Newer Comments &rarr;', 'tribe' ) );
		}

		return $data;
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
	 * @return void
	 */
	public function render_comment( $comment, $args, $depth ): void {
		$classes = get_comment_class( [], $comment, $comment->comment_post_ID );

		if ( in_array( $comment->comment_type, [ 'pingback', 'trackback' ], true ) ) {
			$label  = $comment->comment_type === 'pingback' ? __( 'Pingback:', 'tribe' ) : __( 'Trackback:', 'tribe' );
			$author = get_comment_author_link( $comment->comment_ID );
			$edit   = $this->comment_edit_link( $comment->comment_ID, __( '(Edit)', 'tribe' ) );
			echo $this->factory->get( Trackback::class, [
				Trackback::COMMENT_ID  => $comment->comment_ID,
				Trackback::LABEL       => $label,
				Trackback::AUTHOR_LINK => $author,
				Trackback::EDIT_LINK   => $edit,
				Trackback::CLASSES     => $classes,
			] )->render();

			return;
		}

		echo $this->factory->get( Comment::class, [
			Comment::COMMENT_ID         => $comment->comment_ID,
			Comment::AUTHOR             => get_comment_author( $comment ),
			Comment::EDIT_LINK          => $this->comment_edit_link( $comment->comment_ID, __( 'Edit Comment', 'tribe' ) ),
			Comment::GRAVATAR           => get_avatar( $comment, 150 ),
			Comment::CLASSES            => $classes,
			Comment::COMMENT_TEXT       => get_comment_text( $comment ),
			Comment::MODERATION_MESSAGE => $comment->comment_approved == '0' ? __( 'Your comment is awaiting moderation.', 'tribe' ) : '',
			Comment::REPLY_LINK         => get_comment_reply_link( array_merge( $args, [
				'reply_text' => __( 'Reply', 'tribe' ),
				'depth'      => $depth,
				'max_depth'  => $args['max_depth'],
			] ) ),
			Comment::TIMESTAMP          => get_comment_time( 'U' ),
		] )->render();
	}

	private function comment_edit_link( $comment_id, $text ): string {
		$url = get_edit_comment_link( $comment_id );
		if ( empty( $url ) ) {
			return '';
		}

		return $this->factory->get( Link::class, [
			Link::CLASSES => [ 'comment-edit-link' ],
			Link::CONTENT => $text,
			Link::URL     => $url,
		] )->render();
	}
}
