<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\footer\navigation;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Nav_Menus\Menu;
use Tribe\Project\Nav_Menus\Nav_Menus_Definer;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Navigation_Controller extends Abstract_Controller {
	public const CLASSES          = 'classes';
	public const ATTRS            = 'attrs';
	public const NAV_LIST_CLASSES = 'nav_list_classes';

	private array  $classes;
	private array  $attrs;
	private array  $nav_list_classes;
	private string $location;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes          = (array) $args[ self::CLASSES ];
		$this->attrs            = (array) $args[ self::ATTRS ];
		$this->nav_list_classes = (array) $args[ self::NAV_LIST_CLASSES ];
		$this->location         = Nav_Menus_Definer::SECONDARY;
	}

	protected function defaults(): array {
		return [
			self::CLASSES          => [],
			self::ATTRS            => [
				'aria-label' => esc_html__( 'Secondary Navigation', 'tribe' ),
			],
			self::NAV_LIST_CLASSES => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES          => [ 'site-footer__nav' ],
			self::NAV_LIST_CLASSES => [ 'site-footer__nav-list' ],
		];
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_nav_list_classes(): string {
		return Markup_Utils::class_attribute( $this->nav_list_classes );
	}

	public function has_menu(): bool {
		return has_nav_menu( $this->location );
	}

	public function get_menu(): string {
		$args = [
			'theme_location'  => $this->location,
			'container'       => false,
			'container_class' => '',
			'menu_class'      => '',
			'menu_id'         => '',
			'depth'           => 1,
			'items_wrap'      => '%3$s',
			'fallback_cb'     => false,
			'item_spacing'    => 'discard',
		];

		return Menu::menu( $args );
	}
}
