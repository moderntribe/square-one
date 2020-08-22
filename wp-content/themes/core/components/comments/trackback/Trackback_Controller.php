<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\comments\trackback;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\comments\Comment_Edit_Link;

class Trackback_Controller extends Abstract_Controller {
	use Comment_Edit_Link;

	public const COMMENT_ID = 'comment_id';
	public const CLASSES    = 'classes';
	public const ATTRS      = 'attrs';
	public const LABEL      = 'label';

	private int $comment_id;
	private array $classes;
	private array $attrs;
	private string $label;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->comment_id = (int) $args[ self::COMMENT_ID ];
		$this->classes    = (array) $args[ self::CLASSES ];
		$this->attrs      = (array) $args[ self::ATTRS ];
		$this->label      = (string) $args[ self::LABEL ];
	}

	protected function defaults(): array {
		return [
			self::COMMENT_ID => 0,
			self::CLASSES    => [],
			self::ATTRS      => [],
			self::LABEL      => __( 'Trackback', 'tribe' ),
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
	public function get_classes(): string {
		return Markup_Utils::class_attribute( get_comment_class( $this->classes ) );
	}

	/**
	 * @return string
	 */
	public function get_attrs(): string {
		$attrs       = $this->attrs;
		$attrs['id'] = sprintf( 'comment-%d', $this->comment_id );

		return Markup_Utils::concat_attrs( $attrs );
	}

	public function get_edit_link(): string {
		return $this->build_edit_link( __( '(Edit)', 'tribe' ) );
	}

	public function get_label(): string {
		return $this->label;
	}

	public function get_trackback_link(): string {
		return get_comment_author_link( $this->comment_id );
	}

}
