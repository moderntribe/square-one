<?php
declare( strict_types=1 );

namespace Tribe\Project\Panels;

use Tribe\Libs\Container\Abstract_Subscriber;

class Panels_Subscriber extends Abstract_Subscriber {
	public function register(): void {

		add_action( 'plugins_loaded', function () {
			$initializer = $this->container->get( Initializer::class );
			$initializer->set_labels();
			foreach ( $this->container->get( Panels_Definer::TYPES ) as $type ) {
				$initializer->add_panel_config( $type );
			}
		}, 9, 0 );

		add_action( 'panels_init', function () {
			$this->container->get( Initializer::class )->initialize_panels( \ModularContent\Plugin::instance() );
		}, 10, 0 );

		add_filter( 'panels_js_config', function ( $data ) {
			return $this->container->get( Initializer::class )->modify_js_config( $data );
		}, 10, 1 );

		if ( ! defined( 'TRIBE_DISABLE_PANELS_CACHE' ) || ! TRIBE_DISABLE_PANELS_CACHE ) {
			add_action( 'the_panels', function () {
				Caching_Loop::preempt_panels_loop();
			}, 0, 0 );
		}

	}

}
