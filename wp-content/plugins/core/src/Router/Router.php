<?php

namespace Tribe\Project\Router;

use FastRoute;
use Tribe\Project\Components\Handler;

/**
 * Class Router
 *
 * @package Tribe\Project\Routes
 */
class Router {

	/**
	 * @var
	 */
	private $instance;

	/**
	 * @var Route_Config[] $routes
	 */
	private $routes = [];

	/**
	 * @var array
	 */
	private $controllers = [];

	/**
	 * @var Handler
	 */
	private $handler;

	public function __construct( Handler $handler ) {
		$this->handler = $handler;
	}

	/**
	 * @return Route_Config[]
	 */
	public function routes() {
		return $this->routes;
	}

	/**
	 * @param $route
	 * @param $callback
	 */
	public function get( $route, $callback ) {
		$config = new Route_Config( 'GET', $route, $callback );

		$this->add_route( $config );
	}

	/**
	 * @param $route
	 * @param $callback
	 */
	public function post( $route, $callback ) {
		$config = new Route_Config( 'POST', $route, $callback );

		$this->add_route( $config );
	}

	/**
	 * @param Route_Config $route
	 */
	public function add_route( Route_Config $route ) {
		$this->routes[] = $route;
	}

	/**
	 * @param       $uri
	 * @param array $args
	 */
	private function get_controllers( $uri, array $args = [] ) {
		$controller_files = scandir( dirname( __FILE__ ) . '/../Controllers' );

		$controller_files = array_filter( $controller_files, function ( $filename ) {
			return strpos( $filename, '.php' ) !== false && $filename !== 'Controller.php';
		} );

		foreach ( $controller_files as $file ) {
			$basename                       = str_replace( '.php', '', $file );
			$classname                      = sprintf( 'Tribe\\Project\\Controllers\\%s', $basename );
			$this->controllers[ $basename ] = new $classname( $this->handler );
		}
	}

	/**
	 * Dispatch request.
	 *
	 * @return bool
	 */
	public function dispatch() {
		$instance = $this;

		do_action( 'tribe/project/router/before_dispatch', $instance );

		$dispatcher = FastRoute\simpleDispatcher( function ( FastRoute\RouteCollector $r ) use ( $instance ) {
			foreach ( $instance->routes() as $route ) {
				$r->addRoute( $route->method(), $route->route(), $route->callback() );
			}
		} );

		$method = $_SERVER['REQUEST_METHOD'];
		$uri    = $_SERVER['REQUEST_URI'];

		if ( false !== $pos = strpos( $uri, '?' ) ) {
			$uri = substr( $uri, 0, $pos );
		}

		$uri       = rawurldecode( $uri );
		$routeInfo = $dispatcher->dispatch( $method, $uri );
		$args      = isset( $routeInfo[2] ) ? $routeInfo[2] : [];

		$this->get_controllers( $uri, $args );

		switch ( $routeInfo[0] ) {
			case FastRoute\Dispatcher::FOUND:
				$handler = explode( '@', $routeInfo[1] );
				$vars    = $routeInfo[2];

				return $this->handle_route( $handler[0], $handler[1], $vars );
			case FastRoute\Dispatcher::NOT_FOUND:
			case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
			default:
				return false;
		}
	}

	/**
	 * @param $controller
	 * @param $method
	 * @param $vars
	 *
	 * @return bool
	 */
	private function handle_route( $controller, $method, $vars ) {
		if ( ! array_key_exists( $controller, $this->controllers ) ) {
			return false;
		}

		$this->controllers[ $controller ]->$method( $vars );

		return true;
	}
}
