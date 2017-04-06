<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Panels;

class Panel_Intializer_Provider implements ServiceProviderInterface {
	public function register( Container $container ) {
		$container[ 'panels.init' ] = function ( $container ) {
			return new Panels\Initializer( $container[ 'plugin_file' ] );
		};

		add_action( 'plugins_loaded', function () use ( $container ) {
			$container[ 'panels.init' ]->hook();
		} );
	}
}