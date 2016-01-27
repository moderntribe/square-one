<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Libs\Cache\Cache;
use Tribe\Libs\Cache\Purger;
use Tribe\Project\Cache\Listener;

class Cache_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {

		$container['cache'] = function( $container ) {
			return new Cache();
		};

		$container['cache.listener'] = function( $container ) {
			return new Listener( $container['cache'] );
		};

		$container['cache.purger'] = function( $container ) {
			return new Purger();
		};

		$container['service_loader']->enqueue( 'cache.listener', 'hook' );
		$container['service_loader']->enqueue( 'cache.purger', 'hook' );
	}
}