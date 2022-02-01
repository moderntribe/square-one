<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\comments\comment;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\comments\Comment_Edit_Link;
use Tribe\Project\Templates\Components\text\Text_Controller;

class Comment_Controller extends Abstract_Controller {

	use Comment_Edit_Link;

	public const ATTRS      = 'attrs';
	public const CLASSES    = 'classes';
	public const COMMENT_ID = 'comment_id';
	public const DEPTH      = 'depth';
	public const MAX_DEPTH  = 'max_depth';
	public const STYLE      = 'style';

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;
	private int $comment_id;
	private int $depth;
	private int $max_depth;
	private string $style;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs      = (array) $args[ self::ATTRS ];
		$this->classes    = (array) $args[ self::CLASSES ];
		$this->comment_id = (int) $args[ self::COMMENT_ID ];
		$this->depth      = (int) $args[ self::DEPTH ];
		$this->max_depth  = (int) $args[ self::MAX_DEPTH ];
		$this->style      = (string) $args[ self::STYLE ];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( get_comment_class( $this->classes ) );
	}

	public function get_attrs(): string {
		$attrs       = $this->attrs;
		$attrs['id'] = sprintf( 'comment-%d', $this->comment_id );

		return Markup_Utils::concat_attrs( $attrs );
	}

	public function get_gravatar(): string {
		$gravatar = get_avatar( $this->comment_id, 150 );

		if ( ! $gravatar ) {
			return '';
		}

		return $gravatar;
	}

	public function get_moderation_message_args(): array {
		$status = wp_get_comment_status( $this->comment_id );

		if ( 'unapproved' !== $status ) {
			return [];
		}

		return [
			Text_Controller::CONTENT => __( 'Your comment is awaiting moderation.', 'tribe' ),
			Text_Controller::CLASSES => [ 'comment__message-moderation ' ],
		];
	}

	public function get_edit_link(): string {
		return $this->build_edit_link( __( 'Edit Comment', 'tribe' ) );
	}

	public function get_reply_link(): string {
		return get_comment_reply_link( [
			'reply_text' => __( 'Reply', 'tribe' ),
			'depth'      => $this->depth,
			'max_depth'  => $this->max_depth,
			'before'     => '<p class="comment__action-reply">',
			'after'      => '</p>',
		] ) ?: '';
	}

	public function get_time( string $format = 'c' ): string {
		return get_comment_time( $format );
	}

	public function get_tag(): string {
		$element = 'li';
		if ( 'div' === $this->style ) {
			$element = 'div';
		}

		return $element;
	}

	protected function defaults(): array {
		return [
			self::ATTRS      => [],
			self::CLASSES    => [],
			self::COMMENT_ID => 0,
			self::DEPTH      => 0,
			self::MAX_DEPTH  => - 1,
			self::STYLE      => 'ol',
		];
	}

	protected function required(): array {
		return [];
	}

}
