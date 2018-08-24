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

		add_action(
			'customize_register', function( \WP_Customize_Manager $wp_customize ) use ( $container ) {
				$container['theme_customizer.loader']->register_customizer_controls( $wp_customize );
			}, 10, 1
		);
	}
}