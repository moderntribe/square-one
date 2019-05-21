<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Tribe\Project\Container\Service_Provider;
use Tribe\Project\CLI\Cache_Prime;

class CLI_Provider extends Service_Provider {
	const CACHE_PRIME      = 'cli.cache-prime';

	public function register( Container $container ) {

		$container[ self::CACHE_PRIME ] = function () {
			return new Cache_Prime();
		};

		add_action( 'init', function () use ( $container ) {
			if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
				return;
			}

			$container[ self::CACHE_PRIME ]->register();
		}, 0, 0 );
	}
}
