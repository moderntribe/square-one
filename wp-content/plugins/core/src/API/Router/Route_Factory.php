<?php
/**
 * PHP Router.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Router;

use Tribe\Project\API\Controllers\Servable;
use Tribe\Project\API\Router\Input_Strategies\Input_Get;
use Tribe\Project\API\Router\Input_Strategies\Input_Passed;

/**
 * Class Config_Factory.
 */
class Route_Factory {
	/**
	 * Generate a Route config.
	 *
	 * @param string   $method     The supported HTTP method.
	 * @param string   $route      The endpoint slug.
	 * @param Servable $controller The route controller.
	 *
	 * @return Route
	 */
	public function make( string $method, string $route, Servable $controller ): Route {
		if ( Router::CREATABLE === $method ) {
			$input_strategy = new Input_Passed();
		} else {
			$input_strategy = new Input_Get();
		}

		return new Route( $method, $route, $controller, $input_strategy );
	}
}
