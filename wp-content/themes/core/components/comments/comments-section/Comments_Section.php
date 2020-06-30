<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Comments;

use Tribe\Project\Templates\Components\Component;
use Tribe\Project\Templates\Components\Link;

class Comments_Section extends Component {

	public const PASSWORD_REQUIRED = 'post_password_required';
	public const HAVE_COMMENTS     = 'have_comments';
	public const OPEN              = 'open';
	public const TITLE             = 'title';
	public const COMMENTS          = 'comments';
	public const FORM              = 'form';
	public const PAGINATION        = 'pagination';

	protected function defaults(): array {
		return [
			self::PASSWORD_REQUIRED => false,
			self::HAVE_COMMENTS     => false,
			self::OPEN              => false,
			self::TITLE             => '',
			self::COMMENTS          => '',
			self::FORM              => '',
			self::PAGINATION        => '',
		];
	}

	public function init() {
		$this->data[ self::FORM ]       = $this->get_comment_form();
		$this->data[ self::PAGINATION ] = $this->get_pagination();
		$this->data[ self::TITLE ]      = $this->get_title();
		$this->data[ self::COMMENTS ]   = $this->get_comments();
	}

	protected function get_title() {
		return sprintf( _nx( __( '%1$s Comment', 'tribe' ), __( '%1$s Comments', 'tribe' ), get_comments_number(), 'comments title' ), number_format_i18n( get_comments_number() ) );
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

			$trackback = new Trackback( [
				Trackback::COMMENT_ID  => $comment->comment_ID,
				Trackback::LABEL       => $label,
				Trackback::AUTHOR_LINK => $author,
				Trackback::EDIT_LINK   => $edit,
				Trackback::CLASSES     => $classes,
			] );

			echo $trackback->get_render();

			return;
		}

		$comment = new Comment( [
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
		] );

		echo $comment->get_render();
	}

	private function comment_edit_link( $comment_id, $text ): string {
		$url = get_edit_comment_link( $comment_id );
		if ( empty( $url ) ) {
			return '';
		}

		$link = new Link( [
			Link::CLASSES => [ 'comment-edit-link' ],
			Link::CONTENT => $text,
			Link::URL     => $url,
		] );

		return $link->get_render();
	}

	public function render(): void {
		?>
        {% if post_password_required == false %}

        <div id="comments" class="comments" data-js="comment-form">

            {% if have_comments %}

            <h6 class="comments__title h4">{{ title }}</h6>

            <ol class="comments__list">
                {{ comments }}
            </ol>

            {% if pagination.paged %}

            <nav class="pagination pagination--comments" aria-labelledby="pagination__label-comments">

                <h3 id="pagination__label-comments" class="u-visually-hidden">{{ __('Comments Pagination') }}</h3>

                <ol class="pagination__list">

                    {% if pagination.previous %}
                    <li class="pagination__item pagination__item--previous">
                        {{ pagination.previous }}
                    </li>
                    {% endif %}

                    {% if pagination.next %}
                    <li class="pagination__item pagination__item--next">
                        {{ pagination.next }}
                    </li>
                    {% endif %}

                </ol>

            </nav>

            {% endif %}

            {% if open == false %}
            <p class="comments__none">{{ __( 'Comments are closed.' )|esc_html }}</p>
            {% endif %}

            {% endif %}

            {{ form }}

        </div>

        {% endif %}
		<?php
	}
}
