<?php declare(strict_types=1);

namespace Tribe\Project\Nav_Menus;

use Tribe\Libs\Cache\Cache;

class Menu {

	/**
	 * @var mixed[]
	 */
	private array $args;

	/**
	 * @var mixed[]
	 */
	private array $cache_key = [];

	private function __construct( array $args ) {
		$this->args         = $args;
		$this->args['echo'] = false;
		$this->build_cache_key();
	}

	/**
	 * A caching wrapper around wp_nav_menu. Always returns
	 * the value, does not echo.
	 *
	 * @param array $args
	 *
	 * @see \wp_nav_menu()
	 *
	 * @return string
	 */
	public static function menu( array $args ): string {
		$menu = new self( $args );

		return $menu->get_html();
	}

	private function build_cache_key(): void {
		$cache_key = [
			'args' => $this->args,
			'url ' => $_SERVER['REQUEST_URI'] ?? '',
		];

		if ( isset( $cache_key['args']['walker'] ) && is_object( $cache_key['args']['walker'] ) ) {
			$cache_key['args']['walker'] = get_class( $cache_key['args']['walker'] );
		}

		$this->cache_key = $cache_key;
	}

	private function get_html(): string {
		$cache = new Cache();
		$nav   = $cache->get( $this->cache_key, 'tribe_nav_menu' );

		if ( empty( $nav ) ) {
			$nav = wp_nav_menu( $this->args );
			$cache->set( $this->cache_key, $nav, 'tribe_nav_menu' );
		}

		return (string) $nav;
	}

}
