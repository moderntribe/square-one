<?php
/**
 * Interface for a router service.
 *
 * @package    SquareOne
 * @subpackage API
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Router;

use Tribe\Project\API\Controllers\Servable;

/**
 * Interface Router_Service.
 */
interface Router_Service {

	/**
	 * Adds a route to the API.
	 *
	 * @param string   $method
	 * @param string   $route
	 * @param Servable $controller
	 */
	public function add_route( string $method, string $route, Servable $controller ): void;

	/**
	 * @return array Registered routes.
	 */
	public function get_routes(): array;

	/**
	 * Dispatch a request.
	 */
	public function dispatch(): void;
}
