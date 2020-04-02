<?php
declare( strict_types=1 );

namespace Tribe\Project\Cache;

use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Subscriber_Interface;

class Cache_Subscriber implements Subscriber_Interface {
	public function register( ContainerInterface $container ): void {
		$this->listen( $container );
		// TODO: Implement register() method.
	}

	protected function listen( ContainerInterface $container ) {
		add_action( 'save_post', function( ...$args ) use ( $container ) {
			$container->get( Listener::class )->save_post( ...$args );
		}, 10, 2 );

		$update_menu = function() use ( $container ) {
			$container->get( Listener::class )->update_menu();
		};
		add_action( 'wp_update_nav_menu_item', $update_menu, 10, 0 );
		add_action( 'wp_create_nav_menu', $update_menu, 10, 0 );
		add_action( 'wp_delete_nav_menu', $update_menu, 10, 0 );


		add_action( 'p2p_created_connection', function( ...$args ) use ( $container ) {
			$container->get( Listener::class )->p2p_created_connection( ...$args );
		}, 10, 1 );
		add_action( 'p2p_delete_connections', function( ...$args ) use ( $container ) {
			$container->get( Listener::class )->p2p_delete_connections( ...$args );
		}, 10, 1 );
	}

}
