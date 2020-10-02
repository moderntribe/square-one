<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\header\navigation;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Nav_Menus\Menu;
use Tribe\Project\Nav_Menus\Nav_Menus_Definer;
use Tribe\Project\Nav_Menus\Walker\Walker_Nav_Menu_Primary;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Header_Navigation_Controller extends Abstract_Controller {
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
		$this->location         = Nav_Menus_Definer::PRIMARY;
	}

	protected function defaults(): array {
		return [
			self::CLASSES => [ 'site-header__nav' ],
			self::ATTRS   => [
				'aria-label' => esc_html__( 'Primary Navigation', 'tribe' ),
			],
			self::NAV_LIST_CLASSES => [ 'site-header__nav-list' ],
		];
	}

	protected function required(): array {
		return [];
	}

	public function has_menu(): bool {
		return has_nav_menu( $this->location );
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

	public function get_menu(): string {
		$args = [
			'theme_location'  => $this->location,
			'container'       => false,
			'container_class' => '',
			'menu_class'      => '',
			'menu_id'         => '',
			'depth'           => 3,
			'items_wrap'      => '%3$s',
			'fallback_cb'     => false,
			'walker'          => new Walker_Nav_Menu_Primary(),
		];

		return Menu::menu( $args );
	}
}
