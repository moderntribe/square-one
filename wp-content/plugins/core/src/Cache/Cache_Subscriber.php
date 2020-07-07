<?php
declare( strict_types=1 );

namespace Tribe\Project\Cache;

use Tribe\Libs\Container\Abstract_Subscriber;

class Cache_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		$this->listen();
	}

	protected function listen(): void {
		add_action( 'save_post', function ( ...$args ) {
			$this->container->get( Listener::class )->save_post( ...$args );
		}, 10, 2 );

		$update_menu = function () {
			$this->container->get( Listener::class )->update_menu();
		};
		add_action( 'wp_update_nav_menu_item', $update_menu, 10, 0 );
		add_action( 'wp_create_nav_menu', $update_menu, 10, 0 );
		add_action( 'wp_delete_nav_menu', $update_menu, 10, 0 );


		add_action( 'p2p_created_connection', function ( ...$args ) {
			$this->container->get( Listener::class )->p2p_created_connection( ...$args );
		}, 10, 1 );
		add_action( 'p2p_delete_connections', function ( ...$args ) {
			$this->container->get( Listener::class )->p2p_delete_connections( ...$args );
		}, 10, 1 );
	}
}
