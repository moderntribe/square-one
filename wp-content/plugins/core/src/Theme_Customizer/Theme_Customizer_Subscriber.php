<?php
declare( strict_types=1 );

namespace Tribe\Project\Theme_Customizer;

use Psr\Container\ContainerInterface;
use Tribe\Project\Container\Subscriber_Interface;

class Theme_Customizer_Subscriber implements Subscriber_Interface {
	public function register( ContainerInterface $container ): void {
		add_action( 'customize_register', function(  \WP_Customize_Manager $wp_customize  ) use ( $container ) {
			$container->get( Customizer_Loader::class )->register_customizer_controls( $wp_customize );
		}, 10, 1 );
	}
}
