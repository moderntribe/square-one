<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Libs\Cache\Cache;
use Tribe\Libs\Cache\Purger;
use Tribe\Project\Cache\Listener;

class Cache_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {

		$container[ 'cache' ] = function ( $container ) {
			return new Cache();
		};

		$container[ 'cache.listener' ] = function ( $container ) {
			return new Listener( $container[ 'cache' ] );
		};

		$container[ 'cache.purger' ] = function ( $container ) {
			return new Purger();
		};

		add_action( 'init', function () use ( $container ) {
			$container[ 'cache.listener' ]->hook();
			$container[ 'cache.purger' ]->hook();
		}, 0, 0 );
	}
}