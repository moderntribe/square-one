<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\sidebar;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Sidebar_Controller extends Abstract_Controller {
	public const SIDEBAR_ID = 'sidebar_id';
	public const CLASSES    = 'classes';
	public const ATTRS      = 'attrs';

	private string $sidebar_id;
	private array  $classes;
	private array  $attrs;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->sidebar_id = (string) $args[ self::SIDEBAR_ID ];
		$this->classes    = (array) $args[ self::CLASSES ];
		$this->attrs      = (array) $args[ self::ATTRS ];
	}

	protected function defaults(): array {
		return [
			self::SIDEBAR_ID => '',
			self::CLASSES    => [],
			self::ATTRS      => [ 'role' => 'complementary' ],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES => [ 'sidebar' ],
		];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function is_active(): bool {
		return $this->sidebar_id && is_active_sidebar( $this->sidebar_id );
	}

	public function get_sidebar_id(): string {
		return $this->sidebar_id;
	}

}
