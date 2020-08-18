<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\comments\comment;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\comments\Comment_Edit_Link;

/**
 * Class Comment
 */
class Comment_Controller extends Abstract_Controller {
	use Comment_Edit_Link;

	public const COMMENT_ID = 'comment_id';
	public const CLASSES    = 'classes';
	public const ATTRS      = 'attrs';
	public const DEPTH      = 'depth';
	public const MAX_DEPTH  = 'max_depth';

	private int   $comment_id;
	private array $classes;
	private array $attrs;
	private int   $depth;
	private int   $max_depth;


	/**
	 * Comment constructor.
	 *
	 * @param array $args
	 */
	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->comment_id = (int) $args[ self::COMMENT_ID ];
		$this->classes    = (array) $args[ self::CLASSES ];
		$this->attrs      = (array) $args[ self::ATTRS ];
		$this->depth      = (int) $args[ self::DEPTH ];
		$this->max_depth  = (int) $args[ self::MAX_DEPTH ];
	}

	/**
	 * @return array
	 */
	protected function defaults(): array {
		return [
			'comment_id' => 0,
			'classes'    => [],
			'attrs'      => [],
			'depth'      => 0,
			'max_depth'  => - 1,
		];
	}

	/**
	 * @return array
	 */
	protected function required(): array {
		return [];
	}

	/**
	 * @return string
	 */
	public function classes(): string {
		return Markup_Utils::class_attribute( get_comment_class( $this->classes ) );
	}

	/**
	 * @return string
	 */
	public function attributes(): string {
		$attrs       = $this->attrs;
		$attrs['id'] = sprintf( 'comment-%d', $this->comment_id );

		return Markup_Utils::concat_attrs( $attrs );
	}

	/**
	 * @return string
	 */
	public function get_gravatar() {
		$gravatar = get_avatar( $this->comment_id, 150 );
		if ( ! $gravatar ) {
			return '';
		}

		return $gravatar;
	}

	public function get_moderation_message() {
		$status = wp_get_comment_status( $this->comment_id );
		if ( 'unapproved' !== $status ) {
			return '';
		}

		return tribe_template_part( 'components/text/text', null, [
			'content' => __( 'Your comment is awaiting moderation.', 'tribe' ),
			'classes' => [ 'comment__message-moderation ' ],
		] );
	}

	public function edit_link(): string {
		return $this->build_edit_link( __( 'Edit Comment', 'tribe' ) );
	}

	public function reply_link(): string {
		return get_comment_reply_link( [
			'reply_text' => __( 'Reply', 'tribe' ),
			'depth'      => $this->depth,
			'max_depth'  => $this->max_depth,
			'before'     => '<p class="comment__action-reply">',
			'after'      => '</p>',
		] ) ?: ''; // because WP does not give a consistent return type
	}

	public function time( $format = 'c' ): string {
		return date( $format, get_comment_time( 'U' ) );
	}
}
