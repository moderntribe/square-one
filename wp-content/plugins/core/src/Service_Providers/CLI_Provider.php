<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\CLI\CPT_Generator;
use Tribe\Project\CLI\Meta_Generator;
use Tribe\Project\CLI\Pimple_Dump;
use Tribe\Project\CLI\Taxonomy_Generator;

class CLI_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {

		$container['cli.pimple_dump'] = function ( $container ) {
			return new Pimple_Dump( $container );
		};

		$container['cli.cpt-generator'] = function ( $container ) {
			return new CPT_Generator();
		};

		$container['cli.taxonomy-generator'] = function ( $container ) {
			return new Taxonomy_Generator();
		};

		$container['cli.meta-generator'] = function ( $container ) {
			return new Meta_Generator();
		};

		add_action( 'init', function () use ( $container ) {
			$container['cli.pimple_dump']->register();
			$container['cli.cpt-generator']->register();
			$container['cli.taxonomy-generator']->register();
			$container['cli.meta-generator']->register();
		}, 0, 0 );
	}
}