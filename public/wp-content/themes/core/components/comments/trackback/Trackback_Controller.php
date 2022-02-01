<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\comments\trackback;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\comments\Comment_Edit_Link;

class Trackback_Controller extends Abstract_Controller {

	use Comment_Edit_Link;

	public const ATTRS      = 'attrs';
	public const CLASSES    = 'classes';
	public const COMMENT_ID = 'comment_id';
	public const LABEL      = 'label';

	/**
	 * @var string[]
	 */
	private array $attrs;

	/**
	 * @var string[]
	 */
	private array $classes;
	private int $comment_id;
	private string $label;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs      = (array) $args[ self::ATTRS ];
		$this->classes    = (array) $args[ self::CLASSES ];
		$this->comment_id = (int) $args[ self::COMMENT_ID ];
		$this->label      = (string) $args[ self::LABEL ];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( get_comment_class( $this->classes ) );
	}

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

	protected function defaults(): array {
		return [
			self::ATTRS      => [],
			self::CLASSES    => [],
			self::COMMENT_ID => 0,
			self::LABEL      => __( 'Trackback', 'tribe' ),
		];
	}

	protected function required(): array {
		return [];
	}

}
