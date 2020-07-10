<?php

namespace Tribe\Project\Controllers;

use Tribe\Project\Components\Component_Factory;
use Tribe\Project\Components\Handler;

/**
 * Class Controller
 *
 * @package Tribe\Project\Controllers
 */
class Controller {

	/**
	 * @var Component_Factory
	 */
	protected $factory;

	/**
	 * @var Handler
	 */
	protected $handler;

	/**
	 * Controller constructor.
	 *
	 * @param Handler $handler
	 * @param Component_Factory $factory
	 */
	public function __construct( Handler $handler, Component_Factory $factory ) {
		$this->handler = $handler;
		$this->factory = $factory;
	}

	/**
	 * @param $component
	 * @param $args
	 */
	protected function render_component( $component, $args ) {
		$this->handler->render_component( $component, $args );
	}
}
