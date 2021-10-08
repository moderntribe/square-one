<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components\navigation;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Nav_Menus\Menu;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Navigation_Controller extends Abstract_Controller {

	public const ATTRS            = 'attrs';
	public const CLASSES          = 'classes';
	public const MENU             = 'menu';
	public const MENU_LOCATION    = 'menu_location';
	public const NAV_LIST_CLASSES = 'nav_list_classes';
	public const NAV_MENU_ARGS    = 'nav_menu_args';

	private const DEFAULT_NAV_MENU_ARGS = [
		'menu'           => null,
		'container'      => false,
		'menu_class'     => '',
		'menu_id'        => '',
		'depth'          => 1,
		'items_wrap'     => '%3$s',
		'fallback_cb'    => false,
		'theme_location' => '',
	];

	/**
	 * @var array
	 */
	private array $attrs;

	/**
	 * @var array
	 */
	private array $classes;

	/**
	 * @var int|string|\WP_Term
	 */
	private $menu;

	/**
	 * @var string
	 */
	private string $menu_location;

	/**
	 * @var array
	 */
	private array $nav_list_classes;

	/**
	 * @var array
	 */
	private array $nav_menu_args;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->attrs            = (array) $args[ self::ATTRS ];
		$this->classes          = (array) $args[ self::CLASSES ];
		$this->menu             = $args[ self::MENU ];
		$this->menu_location    = (string) $args[ self::MENU_LOCATION ];
		$this->nav_list_classes = (array) $args[ self::NAV_LIST_CLASSES ];
		$this->nav_menu_args    = $this->parse_menu_args( $args[ self::NAV_MENU_ARGS ] );
	}

	public function get_attrs(): string {
		return Markup_Utils::concat_attrs( $this->attrs );
	}

	public function get_classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_nav_list_classes(): string {
		return Markup_Utils::class_attribute( $this->nav_list_classes );
	}

	/**
	 * Returns true if either $menu_id or $menu_location are valid WordPress menus.
	 *
	 * @return bool
	 */
	public function has_menu(): bool {
		return is_nav_menu( $this->menu ) || has_nav_menu( $this->menu_location );
	}

	public function get_menu(): string {
		if ( ! $this->has_menu() ) {
			return '';
		}

		return Menu::menu( $this->nav_menu_args );
	}

	protected function defaults(): array {
		return [
			self::ATTRS            => [],
			self::CLASSES          => [ 'c-nav' ],
			self::MENU             => null,
			self::MENU_LOCATION    => '',
			self::NAV_LIST_CLASSES => [ 'c-nav__list' ],
			self::NAV_MENU_ARGS    => [],
		];
	}

	private function parse_menu_args( $menu_args ): array {
		$menu_args['menu']           = $this->menu;
		$menu_args['theme_location'] = $this->menu_location;

		return wp_parse_args( $menu_args, self::DEFAULT_NAV_MENU_ARGS );
	}

}
