<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Queues\Backends\MySQL;
use Tribe\Project\Queues\Backends\WP_Cache;
use Tribe\Project\Queues\Cron;
use Tribe\Project\Queues\DefaultQueue;
use Tribe\Project\Queues\Queue_Collection;

class Queues_Provider implements ServiceProviderInterface {

	const WP_CACHE      = 'queues.backend.wp_cache';
	const MYSQL         = 'queues.backend.mysql';
	const DEFAULT_QUEUE = 'queues.DefaultQueue';
	const COLLECTION    = 'queues.collection';
	const CRON          = 'queues.cron';

	public function register( Container $container ) {

		$container[ self::WP_CACHE ] = function(){
			return new WP_Cache();
		};

		$container[ self::MYSQL ] = function() {
			return new MySQL();
		};

		$container[ self::DEFAULT_QUEUE ] = function ( $container ) {
			$backend = $container['queues.backend.mysql'];
			return new DefaultQueue( $backend );
		};

		$container[ self::COLLECTION ] = function( $container ) {
			return new Queue_Collection();
		};

		if( ! defined( 'DISABLE_WP_CRON' ) || false === DISABLE_WP_CRON ) {
			$container[ self::CRON ] = function ( $container ) {
				return new Cron();
			};

			add_filter( 'cron_schedules', function ( $schedules ) use ( $container ) {
				return $container[ self::CRON ]->add_interval( $schedules );
			}, 10, 1 );
		}

		add_action( 'init', function() use ( $container ) {
			$container[ self::COLLECTION ]->add( $container[ self::DEFAULT_QUEUE ] );
		}, 0, 0 );

	}
}