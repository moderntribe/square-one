<?php

namespace Tribe\Project\Router;

class Route_Config {

	/**
	 * @var string $method
	 */
	private $method;

	/**
	 * @var string $route
	 */
	private $route;

	/**
	 * @var string $callback
	 */
	private $callback;

	/**
	 * Route_Config constructor.
	 *
	 * @param string $method
	 * @param string $route
	 * @param string $callback
	 */
	public function __construct( string $method, string $route, string $callback ) {
		$this->method   = $method;
		$this->route    = $route;
		$this->callback = $callback;
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
	 * @return string
	 */
	public function callback(): string {
		return $this->callback;
	}

}
