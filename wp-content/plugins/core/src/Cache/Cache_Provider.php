<?php


namespace Tribe\Project\Cache;

use Pimple\Container;

class Cache_Provider extends \Tribe\Libs\Cache\Cache_Provider {
	protected function listen( Container $container ) {
		$container[ self::LISTENER ] = function ( Container $container ) {
			return new Listener( $container[ self::CACHE ] );
		};

		add_action( 'save_post', function( ...$args ) use ( $container ) {
			$container[ self::LISTENER ]->save_post( ...$args );
		}, 10, 2 );

		$update_menu = function( ...$args ) use ( $container ) {
			$container[ self::LISTENER ]->update_menu( ...$args );
		};
		add_action( 'wp_update_nav_menu_item', $update_menu, 10, 0 );
		add_action( 'wp_create_nav_menu', $update_menu, 10, 0 );
		add_action( 'wp_delete_nav_menu', $update_menu, 10, 0 );


		add_action( 'p2p_created_connection', function( ...$args ) use ( $container ) {
			$container[ self::LISTENER ]->p2p_created_connection( ...$args );
		}, 10, 1 );
		add_action( 'p2p_delete_connections', function( ...$args ) use ( $container ) {
			$container[ self::LISTENER ]->p2p_delete_connections( ...$args );
		}, 10, 1 );
	}
}