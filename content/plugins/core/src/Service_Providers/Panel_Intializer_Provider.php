<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Panels;

class Panel_Intializer_Provider implements ServiceProviderInterface {
	public function register( Container $container ) {
		$container['panels.init'] = function( $container ) {
			return new Panels\Initializer( $container['plugin_file'] );
		};

		$container['service_loader']->enqueue( 'panels.init', 'hook' );
	}
}