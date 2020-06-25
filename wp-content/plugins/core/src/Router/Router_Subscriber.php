<?php
declare( strict_types=1 );

namespace Tribe\Project\Router;

use Tribe\Libs\Container\Abstract_Subscriber;
use Tribe\Project\Router\Router;
use Tribe\Project\Router\Routes;
use Tribe\Project\Theme\Config\Image_Sizes;
use Tribe\Project\Theme\Config\Supports;
use Tribe\Project\Theme\Config\Web_Fonts;
use Tribe\Project\Theme\Media\Image_Wrap;
use Tribe\Project\Theme\Media\Oembed_Filter;

class Router_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		add_filter( 'template_include', function( $template ) {
			$has_route = $this->container->get( Router::class )->dispatch();

			if ( ! $has_route ) {
				return $template;
			}

			exit;
		});

		add_action( 'tribe/project/router/before_dispatch', function( $router ) {
			$this->container->get( Routes::class )->add_routes( $router );
		});
	}
}
