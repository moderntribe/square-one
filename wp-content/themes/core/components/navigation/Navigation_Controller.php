<?php declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\navigation;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Nav_Menus\Menu;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Navigation_Controller extends Abstract_Controller {

	public const LOCATION         = 'location';
	public const CLASSES          = 'classes';
	public const ATTRS            = 'attrs';
	public const NAV_LIST_CLASSES = 'nav_list_classes';
	public const NAV_MENU_ARGS    = 'nav_menu_args';

	private const DEFAULT_NAV_MENU_ARGS = [
		'container'   => false,
		'menu_class'  => '',
		'menu_id'     => '',
		'depth'       => 1,
		'items_wrap'  => '%3$s',
		'fallback_cb' => false,
	];

	private string $location;
	private array $classes;
	private array $attrs;
	private array $nav_list_classes;
	private array $nav_menu_args;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->location         = (string) $args[ self::LOCATION ];
		$this->classes          = (array) $args[ self::CLASSES ];
		$this->attrs            = (array) $args[ self::ATTRS ];
		$this->nav_list_classes = (array) $args[ self::NAV_LIST_CLASSES ];
		$this->nav_menu_args    = $this->parse_menu_args( $args[ self::NAV_MENU_ARGS ] );
	}

	protected function defaults(): array {
		return [
			self::LOCATION         => '',
			self::CLASSES          => [ 'c-nav' ],
			self::ATTRS            => [],
			self::NAV_LIST_CLASSES => [ 'c-nav__list' ],
			self::NAV_MENU_ARGS    => [],
		];
	}

	private function parse_menu_args( $menu_args ): array {
		$menu_args['theme_location'] = $this->location;

		return wp_parse_args( $menu_args, self::DEFAULT_NAV_MENU_ARGS );
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
		if ( empty( $this->location ) ) {
			return '';
		}

		return Menu::menu( $this->nav_menu_args );
	}
}
