<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Tribe\Project\CLI\Container_Export;
use Tribe\Project\Container\Service_Provider;
use Tribe\Project\CLI\CLI_Generator;
use Tribe\Project\CLI\Settings_Generator;
use Tribe\Project\CLI\CPT_Generator;
use Tribe\Project\CLI\File_System;
use Tribe\Project\CLI\Meta\Importer;
use Tribe\Project\CLI\Taxonomy_Generator;
use Tribe\Project\CLI\Cache_Prime;

class CLI_Provider extends Service_Provider {
	const CACHE_PRIME      = 'cli.cache-prime';
	const CONTAINER_EXPORT = 'cli.container_export';
	const FILE_SYSTEM      = 'cli.file_system';
	const GENERATE_CPT     = 'cli.generator.cpt';
	const GENERATE_TAX     = 'cli.generator.taxonomy';
	const GENERATE_CLI     = 'cli.generator.cli';
	const GENERATE_SETTING = 'cli.generator.setting';
	const GENERATE_META    = 'cli.generator.meta';

	public function register( Container $container ) {
		$this->generators( $container );

		$container[ self::CONTAINER_EXPORT ] = function ( $container ) {
			return new Container_Export( tribe_project() );
		};

		$container[ self::CACHE_PRIME ] = function () {
			return new Cache_Prime();
		};

		add_action( 'init', function () use ( $container ) {
			if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
				return;
			}

			$container[ self::CONTAINER_EXPORT ]->register();
			$container[ self::CACHE_PRIME ]->register();
			$container[ self::GENERATE_CPT ]->register();
			$container[ self::GENERATE_TAX ]->register();
			$container[ self::GENERATE_CLI ]->register();
			$container[ self::GENERATE_SETTING ]->register();
			$container[ self::GENERATE_META ]->register();
		}, 0, 0 );
	}

	private function generators( Container $container ) {

		$container[ self::FILE_SYSTEM ] = function ( $container ) {
			return new File_System();
		};

		$container[ self::GENERATE_CPT ] = function ( $container ) {
			return new CPT_Generator( $container[ self::FILE_SYSTEM ] );
		};

		$container[ self::GENERATE_TAX ] = function ( $container ) {
			return new Taxonomy_Generator( $container[ self::FILE_SYSTEM ] );
		};

		$container[ self::GENERATE_CLI ]     = function ( $container ) {
			return new CLI_Generator( $container[ self::FILE_SYSTEM ] );
		};
		$container[ self::GENERATE_SETTING ] = function ( $container ) {
			return new Settings_Generator( $container[ self::FILE_SYSTEM ] );
		};

		$container[ self::GENERATE_META ] = function ( $container ) {
			return new Importer( $container[ self::FILE_SYSTEM ] );
		};
	}
}
