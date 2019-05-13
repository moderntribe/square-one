<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Tribe\Project\Container\Service_Provider;
use Tribe\Project\Queues\Backends\MySQL;
use Tribe\Project\Queues\Backends\WP_Cache;
use Tribe\Project\Queues\Cron;
use Tribe\Project\Queues\DefaultQueue;
use Tribe\Project\Queues\Queue_Collection;

class Queues_Provider extends Service_Provider {

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

		$container[ self::COLLECTION ] = function( $container ) {
			return new Queue_Collection();
		};

		$container[ self::CRON ] = function ( $container ) {
			return new Cron();
		};

		if( ! defined( 'DISABLE_WP_CRON' ) || false === DISABLE_WP_CRON ) {
			add_filter( 'cron_schedules', function ( $schedules ) use ( $container ) {
				return $container[ self::CRON ]->add_interval( $schedules );
			}, 10, 1 );
		}

		add_action( 'tribe/project/queues/mysql/init_table', function () use ( $container ) {
			$container[ self::MYSQL ]->initialize_table();
		}, 10, 0 );

		add_action( 'admin_init', function () {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				do_action( 'tribe/project/queues/mysql/init_table' );
			}
		}, 0, 0 );

		$this->register_queues( $container );
	}

	/**
	 * @param Container $container
	 */
	protected function register_queues( Container $container ) {
		$container[ self::DEFAULT_QUEUE ] = function ( $container ) {
			$backend = $container[ self::MYSQL ];

			return new DefaultQueue( $backend );
		};

		add_action( 'init', function () use ( $container ) {
			$container[ self::COLLECTION ]->add( $container[ self::DEFAULT_QUEUE ] );
		}, 0, 0 );
	}

}