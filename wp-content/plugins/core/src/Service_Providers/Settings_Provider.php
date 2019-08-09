<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Tribe\Project\Container\Service_Provider;
use Tribe\Project\Settings;

class Settings_Provider extends Service_Provider {

	public function register( Container $container ) {
		$this->register_pages( $container );
	}

	public function register_pages( Container $container ) {
		$container['settings.general'] = function ( Container $container ) {
			return new Settings\General();
		};
		add_action( 'init', function () use ( $container ) {
			$container['settings.general']->hook();
		}, 0, 0 );
	}
}
