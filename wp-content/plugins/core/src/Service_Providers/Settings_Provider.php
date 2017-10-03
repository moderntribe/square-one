<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Settings;

class Settings_Provider implements ServiceProviderInterface {

	public function register( Container $container ) {
		$this->register_pages( $container );
	}

	public function register_pages( Container $container ) {
		$container[ 'settings.google_api' ] = function ( Container $container ) {
			return new Settings\Places_Settings();
		};

		add_action( 'init', function () use ( $container ) {
			$container['settings.google_api']->register_settings();
		}, 10, 0 );
		add_action( 'init', function () use ( $container ) {
			$container['settings.google_api']->register_fields();
		}, 10, 0 );
	}
}
