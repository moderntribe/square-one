<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\CLI\CPT_Generator;
use Tribe\Project\CLI\Pimple_Dump;

class CLI_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {

		$container['cli.pimple_dump'] = function ( $container ) {
			return new Pimple_Dump( $container );
		};

		$container['cli.cpt-generator'] = function ( $container ) {
			return new CPT_Generator();
		};

		add_action( 'init', function () use ( $container ) {
			$container['cli.pimple_dump']->register();
			$container['cli.cpt-generator']->register();
		}, 0, 0 );
	}
}