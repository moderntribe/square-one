<?php
/**
 * PHP Router.
 *
 * @package SquareOne
 * @subpackage API
 */
declare(strict_types=1);

namespace Tribe\Project\API\Router;

use Elasticsearch\ClientBuilder;
use FastRoute;
use MeekroDB;
use Tribe\API\Config\Config_Factory;
use Tribe\API\Config\Strategies\Input_Get_Config;
use Tribe\API\Config\Strategies\Input_Passed_Config;
use Tribe\API\DB\Meekro_DB_Service;
use Tribe\API\Fetchers\v1\Cache_Fetcher;
use Tribe\API\Fetchers\v1\DB_Fetcher;
use Tribe\API\Fetchers\v1\ES_Fetcher as ES_Fetcher_v1;
use Tribe\API\Fetchers\v2\ES_Fetcher;
use Tribe\API\Results_Processor;

/**
 * Class Router
 *
 * @package Tribe\API\Routes
 */
class Router implements Router_Service {

	/**
	 * @var
	 */
	private static $instance;

	/**
	 * @var Route_Config[] $routes
	 */
	private $routes = [];

	/**
	 * @var array
	 */
	private $controllers = [];

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
	public static function get( $route, $callback ) {
		$instance = self::instance();
		$config   = new Route_Config( 'GET', $route, $callback );

		$instance->add_route( $config );
	}

	/**
	 * @param $route
	 * @param $callback
	 */
	public function post( $route, $callback ) {
		$instance = self::instance();
		$config   = new Route_Config( 'POST', $route, $callback );

		$instance->add_route( $config );
	}

	/**
	 * @param Route_Config $route
	 */
	public function add_route( Route_Config $route ) {
		$this->routes[] = $route;
	}

	/**
	 * Loads the correct controller using a directory scan.
	 *
	 * @param string $uri  The URI passed in by the router.
	 * @param array  $args Arguments passed in by the router, in a POST for instance.
	 */
	private function get_controllers( $uri, array $args = [] ) {
		$processor        = $this->get_processor( $uri, $args );
		$version          = $this->get_api_version( $uri );
		$controller_files = scandir( __DIR__ . '/../Controllers/' . $version );
		$controller_files = array_filter( $controller_files, static function ( $filename ) {
			return strpos( $filename, '.php' ) !== false && $filename !== 'Controller.php';
		} );

		foreach ( $controller_files as $file ) {
			$basename                       = str_replace( '.php', '', $file );
			$classname                      = sprintf( 'Tribe\\API\\Controllers\\%1$s\\%2$s', $version, $basename );
			$this->controllers[ $basename ] = new $classname( $processor );
		}
	}

	/**
	 * @param       $uri
	 *
	 * @param array $args
	 *
	 * @return Results_Processor
	 */
	private function get_processor( $uri, array $args = [] ) {
		$hosts    = [ EP_HOST ];
		$client   = ClientBuilder::create()->setHosts( $hosts )->build();
		$endpoint = $this->get_endpoint_from_uri( $uri );
		$version  = $this->get_api_version( $uri );

		switch ( $endpoint ) {
			case 'filters':
			case 'menus':
				$strategy = new Input_Passed_Config( $args );
				break;
			default:
				$strategy = new Input_Get_Config();
				break;
		}

		$factory = new Config_Factory( $strategy );
		$config  = $factory->generate_config( $endpoint );
		$config->init();

		$db         = new MeekroDB( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
		$db_service = new Meekro_DB_Service( $db );

		switch ( $version ) {
			case 'v2':
				$db_fetcher = $config->is_search() ? new ES_Fetcher( $client, $config, $db_service ) : new DB_Fetcher( $db_service, $config );
				break;
			default:
				$db_fetcher = $config->is_search() ? new ES_Fetcher_v1( $client, $config, $db_service ) : new DB_Fetcher( $db_service, $config );
				break;
		}

		$cache_fetcher = new Cache_Fetcher( $config, __DIR__ );

		return new Results_Processor( $cache_fetcher, $db_fetcher );
	}

	private function get_endpoint_from_uri( $uri ) {
		if ( strpos( $uri, 'changes' ) !== false ) {
			return 'changes';
		}

		if ( strpos( $uri, 'filters' ) !== false ) {
			return 'filters';
		}

		if ( strpos( $uri, 'menus' ) !== false ) {
			return 'menus';
		}

		if ( strpos( $uri, 'rulebooks' ) !== false ) {
			return 'rulebooks';
		}

		if ( strpos( $uri, 'offline' ) !== false ) {
			return 'rulebooks';
		}

		if ( strpos( $uri, 'cache' ) !== false ) {
			return 'cache';
		}

		return 'all';
	}

	public static function dispatch() {
		$instance = self::instance();

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

		$instance->get_controllers( $uri, $args );

		header( 'Content-Type: application/json' );

		switch ( $routeInfo[0] ) {
			case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
				http_response_code( 405 );
				echo 'Method not allowed';
				break;
			case FastRoute\Dispatcher::FOUND:
				$handler = explode( '@', $routeInfo[1] );
				$vars    = $routeInfo[2];
				$instance->handle_route( $handler[0], $handler[1], $vars );
				break;
			case FastRoute\Dispatcher::NOT_FOUND:
			default:
				http_response_code( 404 );
				echo 'Route not found';
				break;
		}
	}

	/**
	 * @param $controller
	 * @param $method
	 * @param $vars
	 */
	public function handle_route( $controller, $method, $vars ) {
		if ( ! array_key_exists( $controller, $this->controllers ) ) {
			http_response_code( 404 );
			echo 'not found';
			exit;
		}

		$this->controllers[ $controller ]->$method( $vars );
	}

	/**
	 * Extracts the API version from the URI.
	 *
	 * @param string $uri Request URI.
	 *
	 * @return string Version string, e.g., v1.
	 */
	private function get_api_version( string $uri ): string {
		return substr( $uri, 5, 2 );
	}

	/**
	 * @return Router
	 */
	private static function instance() {
		if ( ! is_null( self::$instance ) ) {
			return self::$instance;
		}

		self::$instance = new self();

		return self::$instance;
	}
}
