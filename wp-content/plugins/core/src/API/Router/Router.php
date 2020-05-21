<?php
/**
 * PHP Router.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Router;

use FastRoute;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use MeekroDB;
use Tribe\Project\API\Controllers\Servable;

/**
 * Class Router
 *
 * @package Tribe\API\Routes
 */
class Router implements Router_Service {

	// API supported methods.
	public const READABLE  = 'GET';
	public const CREATABLE = 'POST';

	// API responses.
	public const METHOD_NOT_ALLOWED = 'Method not allowed';
	public const ROUTE_NOT_FOUND    = 'Route not found';

	/**
	 * @var string The combination of API base and version number.
	 */
	private $route_base;

	/**
	 * @var Route[] $routes
	 */
	private $routes = [];

	/**
	 * @var Route_Factory
	 */
	private $route_factory;

	/**
	 * Router constructor.
	 *
	 * @param string        $api_base The URL base.
	 * @param string        $version  The API version.
	 * @param Route_Factory $route_factory
	 */
	public function __construct( string $api_base, string $version, Route_Factory $route_factory ) {
		$this->route_base    = sprintf( '%s/%s/', $api_base, $version );
		$this->route_factory = $route_factory;
	}

	/**
	 * @inheritDoc
	 */
	public function add_route( string $method, string $route, Servable $controller ): void {
		$this->routes[] = $this->route_factory->make( $method, $route, $controller );
	}

	/**
	 * @inheritDoc
	 */
	public function get_routes(): array {
		return $this->routes;
	}

	/**
	 * @inheritDoc
	 */
	public function dispatch(): void {
		$dispatcher = FastRoute\simpleDispatcher(
			function ( RouteCollector $r ) {
				foreach ( $this->get_routes() as $index => $route ) {
					$r->addRoute( $route->method(), $this->route_base . $route->route(), (string) $index );
				}
			}
		);

		$method = $_SERVER['REQUEST_METHOD'];
		$uri    = $_SERVER['REQUEST_URI'];
		$pos    = strpos( $uri, '?' );

		if ( false !== $pos ) {
			$uri = substr( $uri, 0, $pos );
		}

		$uri       = rawurldecode( $uri );
		$routeInfo = $dispatcher->dispatch( $method, $uri );

		header( 'Content-Type: application/json' );

		switch ( $routeInfo[0] ) {
			case Dispatcher::METHOD_NOT_ALLOWED:
				http_response_code( 405 );
				echo self::METHOD_NOT_ALLOWED;
				break;
			case Dispatcher::FOUND:
				$controller = $this->routes[ $routeInfo[1] ]->controller();
				$controller->handle_request( $routeInfo[2] );
				break;
			case Dispatcher::NOT_FOUND:
			default:
				http_response_code( 404 );
				echo self::ROUTE_NOT_FOUND;
				break;
		}
	}
}
