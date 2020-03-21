<?php
declare( strict_types=1 );

namespace Tribe\Project\Panels;

use Psr\Container\ContainerInterface;
use Tribe\Project\Container\Subscriber_Interface;

class Panels_Subscriber implements Subscriber_Interface {
	public function register( ContainerInterface $container ): void {

		add_action( 'plugins_loaded', function () use ( $container ) {
			$initializer = $container->get( Initializer::class );
			$initializer->set_labels();
			foreach ( $container->get( 'panels.types' ) as $type ) {
				$initializer->add_panel_config( $type );
			}
		}, 9, 0 );

		add_action( 'panels_init', function () use ( $container ) {
			$container->get( Initializer::class )->initialize_panels( \ModularContent\Plugin::instance() );
		}, 10, 0 );

		add_filter( 'panels_js_config', function ( $data ) use ( $container ) {
			return $container->get( Initializer::class )->modify_js_config( $data );
		}, 10, 1 );

		if ( ! defined( 'TRIBE_DISABLE_PANELS_CACHE' ) || ! TRIBE_DISABLE_PANELS_CACHE ) {
			add_action( 'the_panels', function () {
				Caching_Loop::preempt_panels_loop();
			}, 0, 0 );
		}

	}

}
