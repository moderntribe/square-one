<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\CLI\Pimple_Dump;

class CLI_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {

		$container['cli.pimple_dump'] = function ( $container ) {
			return new Pimple_Dump( $container );
		};

		add_action( 'init', function () use ( $container ) {
			if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
				return;
			}

			\WP_CLI::add_command( 'pimple', $container['cli.pimple_dump'] );

		}, 0, 0 );
	}
}