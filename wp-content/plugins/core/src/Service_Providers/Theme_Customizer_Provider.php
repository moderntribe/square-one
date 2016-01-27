<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Theme_Customizer;

class Theme_Customizer_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {
		$container['theme_customizer.loader'] = function( $container ) {
			return new Theme_Customizer\Customizer_Loader();
		};

		$container['service_loader']->enqueue( 'theme_customizer.loader', 'hook' );
	}
}