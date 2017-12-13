<?php


namespace Tribe\Project\Templates;

use Tribe\Project\Twig\Noop_Lazy_Strings;
use Tribe\Project\Twig\Twig_Template;

class Comments extends Twig_Template {
	public function get_data(): array {
		$password_required = post_password_required();
		$have_comments     = ( ! $password_required ) && have_comments();
		$open              = comments_open();
		$data              = [
			'post_password_required' => $password_required,
			'have_comments'          => $have_comments,
			'open'                   => $open,
			'title'                  => $this->get_title(),
			'comments'               => $have_comments ? $this->get_comments() : '',
			'form'                   => $this->get_comment_form(),
			'pagination'             => $this->get_pagination(),
			'lang'                   => new Noop_Lazy_Strings( 'tribe' ),
		];

		return $data;
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
		$template = new Content\Pagination\Comments( $this->template, $this->twig );
		$data     = $template->get_data();

		return $data[ 'pagination' ];
	}
}
