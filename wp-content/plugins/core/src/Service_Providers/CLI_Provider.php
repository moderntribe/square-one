<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Tribe\Project\CLI\Create_Tables;
use Tribe\Project\Container\Service_Provider;
use Tribe\Project\CLI\Cache_Prime;

class CLI_Provider extends Service_Provider {
	const CACHE_PRIME      = 'cli.cache-prime';
	const CREATE_TABLES      = 'cli.create-tables';

	public function register( Container $container ) {

		$container[ self::CACHE_PRIME ] = static function () {
			return new Cache_Prime();
		};

		$container[ self::CREATE_TABLES ] = static function () {
			return new Create_Tables();
		};

		add_action( 'init', static function () use ( $container ) {
			if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
				return;
			}

			$container[ self::CACHE_PRIME ]->register();
			$container[ self::CREATE_TABLES ]->register();
		}, 0, 0 );
	}
}
