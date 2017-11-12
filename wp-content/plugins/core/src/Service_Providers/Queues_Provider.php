<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Queues\Backends\WP_Cache;
use Tribe\Project\Queues\DefaultQueue;

class Queues_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {

		$container['queues.backend.wp_cache'] = function(){
			return new WP_Cache();
		};

		$container['queues.DefaultQueue'] = function ( $container ) {

			// We probably want a constant based conditional/switch here
			// to allow easy backend change in different environments
			$backend = $container['queues.backend.wp_cache'];

			return new DefaultQueue( $backend );
		};

		add_action( 'plugins_loaded', function () use ($container) {
			$container['queues.DefaultQueue'];
		} );
	}
}