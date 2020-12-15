<?php


namespace Tribe\Project\Nav_Menus;

use Tribe\Libs\Cache\Cache;

class Menu {
	private $args      = [];
	private $cache_key = '';

	private function __construct( $args ) {
		$this->args         = $args;
		$this->args['echo'] = false;
		$this->build_cache_key();
	}

	private function build_cache_key() {
		$cache_key = [
			'args' => $this->args,
			'url ' => isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '',
		];
		if ( isset( $cache_key['args']['walker'] ) && is_object( $cache_key['args']['walker'] ) ) {
			$cache_key['args']['walker'] = get_class( $cache_key['args']['walker'] );
		}
		$this->cache_key = $cache_key;
	}

	private function get_html() {
		$cache = new Cache();
		$nav   = $cache->get( $this->cache_key, 'tribe_nav_menu' );
		if ( empty( $nav ) ) {
			$nav = wp_nav_menu( $this->args );
			$cache->set( $this->cache_key, $nav, 'tribe_nav_menu' );
		}

		return $nav;
	}

	/**
	 * A caching wrapper around wp_nav_menu. Always returns
	 * the value, does not echo.
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function menu( $args ): string {
		$menu = new self( $args );

		return $menu->get_html();
	}
}
