<?php


namespace Tribe\Project\Queues;

use Pimple\Container;
use Tribe\Project\Container\Service_Provider;
use Tribe\Project\Queues\Backends\MySQL;
use Tribe\Project\Queues\Backends\WP_Cache;
use Tribe\Project\Queues\CLI\Add_Tasks;
use Tribe\Project\Queues\CLI\Cleanup;
use Tribe\Project\Queues\CLI\List_Queues;
use Tribe\Project\Queues\CLI\MySQL_Table;
use Tribe\Project\Queues\CLI\Process;

class Queues_Provider extends Service_Provider {

	const WP_CACHE         = 'queues.backend.wp_cache';
	const MYSQL            = 'queues.backend.mysql';
	const DEFAULT_QUEUE    = 'queues.DefaultQueue';
	const COLLECTION       = 'queues.collection';
	const CRON             = 'queues.cron';
	const QUEUES_LIST      = 'queues.cli.list';
	const QUEUES_ADD_TABLE = 'queues.cli.add_table';
	const QUEUES_CLEANUP   = 'queues.cli.cleanup';
	const QUEUES_PROCESS   = 'queues.cli.process';
	const QUEUES_ADD_TASK  = 'queues.cli.add_tasks';


	public function register( Container $container ) {
		$this->backends( $container );
		$this->queues( $container );
		$this->cli( $container );
		$this->cron( $container );
	}

	/**
	 * Register available backends
	 *
	 * @param Container $container
	 *
	 */
	protected function backends( Container $container ) {
		$this->cache_backend( $container );
		$this->mysql_backend( $container );
	}

	protected function cache_backend( Container $container ) {
		$container[ self::WP_CACHE ] = function () {
			return new WP_Cache();
		};
	}

	protected function mysql_backend( Container $container ) {
		$container[ self::MYSQL ] = function () {
			return new MySQL();
		};

		add_action( 'tribe/project/queues/mysql/init_table', function () use ( $container ) {
			$container[ self::MYSQL ]->initialize_table();
		}, 10, 0 );

		add_action( 'admin_init', function () {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				do_action( 'tribe/project/queues/mysql/init_table' );
			}
		}, 0, 0 );
	}

	/**
	 * Register available queues
	 *
	 * @param Container $container
	 *
	 */
	protected function queues( Container $container ) {
		$container[ self::DEFAULT_QUEUE ] = function ( $container ) {
			$backend = $container[ 'queues.backend.mysql' ];

			return new DefaultQueue( $backend );
		};

		$container[ self::COLLECTION ] = function ( $container ) {
			$collection = new Queue_Collection();
			$collection->add( $container[ self::DEFAULT_QUEUE ] );

			return $collection;
		};
	}

	protected function cli( Container $container ) {
		$container[ self::QUEUES_LIST ] = function ( $container ) {
			return new List_Queues( $container[ self::COLLECTION ] );
		};

		$container[ self::QUEUES_ADD_TABLE ] = function ( $container ) {
			return new MySQL_Table( $container[ self::MYSQL ] );
		};

		$container[ self::QUEUES_CLEANUP ] = function ( $container ) {
			return new Cleanup( $container[ self::COLLECTION ] );
		};

		$container[ self::QUEUES_PROCESS ] = function ( $container ) {
			return new Process( $container[ self::COLLECTION ] );
		};

		$container[ self::QUEUES_ADD_TASK ] = function ( $container ) {
			return new Add_Tasks( $container[ self::COLLECTION ] );
		};

		add_action( 'init', function () use ( $container ) {
			if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
				return;
			}

			$container[ self::QUEUES_LIST ]->register();
			$container[ self::QUEUES_ADD_TABLE ]->register();
			$container[ self::QUEUES_CLEANUP ]->register();
			$container[ self::QUEUES_PROCESS ]->register();
			$container[ self::QUEUES_ADD_TASK ]->register();
		}, 0, 0 );
	}

	/**
	 * @param Container $container
	 */
	protected function cron( Container $container ) {
		$container[ self::CRON ] = function ( $container ) {
			return new Cron();
		};

		if ( ! defined( 'DISABLE_WP_CRON' ) || false === DISABLE_WP_CRON ) {
			add_filter( 'cron_schedules', function ( $schedules ) use ( $container ) {
				return $container[ self::CRON ]->add_interval( $schedules );
			}, 10, 1 );


			add_action( 'admin_init', function () use ( $container ) {
				$container[ self::CRON ]->schedule_cron();
			}, 10, 0 );

			add_action( Cron::CRON_ACTION, function () use ( $container ) {
				foreach ( $container[ self::COLLECTION ]->queues() as $queue ) {
					$container[ self::CRON ]->process_queues( $queue );
				}
			}, 10, 0 );
		}
	}
}