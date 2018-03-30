<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\CLI\CLI_Generator;
use Tribe\Project\CLI\Settings_Generator;
use Tribe\Project\CLI\CPT_Generator;
use Tribe\Project\CLI\File_System;
use Tribe\Project\CLI\Pimple_Dump;
use Tribe\Project\CLI\Queues\Add_Tasks;
use Tribe\Project\CLI\Queues\Cleanup;
use Tribe\Project\CLI\Queues\Process;
use Tribe\Project\CLI\Taxonomy_Generator;
use Tribe\Project\CLI\Cache_Prime;
use Tribe\Project\CLI\Queues\Table;
use Tribe\Project\CLI\Queues\MySQL_Table;

class CLI_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {
		$container['cli.file-system'] = function ( $container ) {
			return new File_System();
		};

		$container['cli.pimple_dump'] = function ( $container ) {
			return new Pimple_Dump( $container );
		};

		$container['cli.queues.list'] = function ( $container ) {
			return new Table( $container['queues.collection'] );
		};

		$container['cli.queues.add_table'] = function ( $container ) {
			return new MySQL_Table( $container['queues.backend.mysql'] );
		};

		$container['cli.queues.cleanup'] = function ( $container ) {
			return new Cleanup( $container['queues.collection'] );
		};

		$container['cli.queues.process'] = function ( $container ) {
			return new Process( $container['queues.collection'] );
		};

		$container['cli.queues.add_tasks'] = function ( $container ) {
			return new Add_Tasks( $container['queues.collection'] );
		};

		$container['cli.cpt-generator'] = function ( $container ) {
			return new CPT_Generator( $container['cli.file-system'] );
		};

		$container['cli.taxonomy-generator'] = function ( $container ) {
			return new Taxonomy_Generator( $container['cli.file-system'] );
		};

		$container['cli.cli-generator'] = function ( $container ) {
			return new CLI_Generator( $container['cli.file-system'] );
		};

		$container['cli.cache-prime'] = function() {
			return new Cache_Prime();
		};

		$container['cli.settings_generator'] = function ( $container ) {
			return new Settings_Generator( $container['cli.file-system'] );
		};

		add_action( 'init', function () use ( $container ) {
			if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
				return;
			}

			$container['cli.pimple_dump']->register();
			$container['cli.cpt-generator']->register();
			$container['cli.taxonomy-generator']->register();
			$container['cli.cli-generator']->register();
			$container['cli.cache-prime']->register();
			$container['cli.settings_generator']->register();
			$container['cli.queues.list']->register();
			$container['cli.queues.add_table']->register();
			$container['cli.queues.cleanup']->register();
			$container['cli.queues.process']->register();
			$container['cli.queues.add_tasks']->register();

		}, 0, 0 );
	}
}
