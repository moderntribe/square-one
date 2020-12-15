<?php
declare( strict_types=1 );

namespace Tribe\Project\Theme_Customizer;

use Tribe\Libs\Container\Abstract_Subscriber;

class Theme_Customizer_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		add_action( 'customize_register', function ( \WP_Customize_Manager $wp_customize ) {
			$this->container->get( Customizer_Loader::class )->register_customizer_controls( $wp_customize );
		}, 10, 1 );
	}
}
