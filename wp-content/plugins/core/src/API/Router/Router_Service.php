<?php
/**
 * Interface for a router service.
 *
 * @package    SquareOne
 * @subpackage API
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Router;

/**
 * Interface Router_Service.
 */
interface Router_Service {
	/**
	 * @return
	 */
	public function routes(): array;

	/**
	 * @param $route
	 * @param $callback
	 */
	public static function get( $route, $callback ): void;

	/**
	 * @param $route
	 * @param $callback
	 */
	public function post( $route, $callback ): void;
}
