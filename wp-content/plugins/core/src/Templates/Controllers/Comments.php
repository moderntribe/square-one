<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Components\Comments as Comments_Context;

class Comments extends Abstract_Template {
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
			'callback'   => 'core_comment',
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
			'class_form'         => 'comment-form t-content',
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
}
