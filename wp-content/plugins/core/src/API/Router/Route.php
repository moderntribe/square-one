<?php
/**
 * A registered Route.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Router;

use Tribe\Project\API\Controllers\Servable;
use Tribe\Project\API\Router\Input_Strategies\Input_Strategy_Interface;

/**
 * Class Route.
 */
class Route {

	/**
	 * @var string $method The HTTP method being used.
	 */
	private $method;

	/**
	 * @var string $route The resource route.
	 */
	private $route;

	/**
	 * @var Servable
	 */
	private $controller;

	/**
	 * @var Input_Strategy_Interface
	 */
	private $input_strategy;

	/**
	 * Route_Config constructor.
	 *
	 * @param string                   $method The HTTP method being used.
	 * @param string                   $route  The resource route.
	 * @param Servable                 $controller
	 * @param Input_Strategy_Interface $input_strategy
	 */
	public function __construct( string $method, string $route, Servable $controller, Input_Strategy_Interface $input_strategy ) {
		$this->method         = $method;
		$this->route          = $route;
		$this->controller     = $controller;
		$this->input_strategy = $input_strategy;
	}

	/**
	 * @return string
	 */
	public function method(): string {
		return $this->method;
	}

	/**
	 * @return string
	 */
	public function route(): string {
		return $this->route;
	}

	/**
	 * @return Servable
	 */
	public function controller(): Servable {
		return $this->controller->get_callable();
	}

	/**
	 * @return Input_Strategy_Interface
	 */
	public function input_strategy(): Input_Strategy_Interface {
		return $this->input_strategy;
	}
}
