<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Queues\Backends\MySQL;
use Tribe\Project\Queues\Backends\WP_Cache;
use Tribe\Project\Queues\DefaultQueue;
use Tribe\Project\Queues\Queue_Collection;

class Queues_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {

		$container['queues.backend.wp_cache'] = function(){
			return new WP_Cache();
		};

		$container['queues.backend.mysql'] = function() {
			return new MySQL();
		};

		$container['queues.DefaultQueue'] = function ( $container ) {
			$backend = $container['queues.backend.mysql'];
			return new DefaultQueue( $backend );
		};

		$container['queues.collection'] = function( $container ) {
			return new Queue_Collection();
		};

		add_action( 'init', function() use ( $container ) {
			$container['queues.collection']->add($container['queues.DefaultQueue']);
		}, 0, 0 );

	}
}