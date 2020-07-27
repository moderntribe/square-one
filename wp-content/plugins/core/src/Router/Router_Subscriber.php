<?php
declare( strict_types=1 );

namespace Tribe\Project\Router;

use Tribe\Libs\Container\Abstract_Subscriber;

class Router_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		/**
		 * Perform Route lookup before loading a template.
		 */
		add_filter( 'template_include', function ( $template ) {
			$has_route = $this->container->get( Router::class )->dispatch();

			if ( ! $has_route ) {
				return $template;
			}

			exit;
		} );

		/**
		 * Add our custom routes to the Router.
		 */
		add_action( 'tribe/project/router/before_dispatch', function ( $router ) {
			$this->container->get( Routes::class )->add_routes( $router );
		} );
	}
}
