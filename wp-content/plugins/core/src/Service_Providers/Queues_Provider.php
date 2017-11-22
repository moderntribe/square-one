<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Queues\Backends\Mysql;
use Tribe\Project\Queues\Backends\WP_Cache;
use Tribe\Project\Queues\DefaultQueue;
use Tribe\Project\Queues\TestingQueue;

class Queues_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {

		$container['queues.backend.wp_cache'] = function(){
			return new WP_Cache();
		};

		$container['queues.backend.mysql'] = function() {
			return new Mysql();
		};

		$container['queues.DefaultQueue'] = function ( $container ) {

			// We probably want a constant based conditional/switch here
			// to allow easy backend change in different environments
			$backend = $container['queues.backend.mysql'];

			return new DefaultQueue( $backend );
		};

		$container['queues.TestingQueue'] = function( $container ) {
			return new TestingQueue( $container['queues.backend.mysql'] );
		};

		add_action( 'plugins_loaded', function () use ($container) {
			$container['queues.DefaultQueue'];
			$container['queues.TestingQueue'];
		} );
	}
}