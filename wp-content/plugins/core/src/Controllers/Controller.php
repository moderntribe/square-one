<?php

namespace Tribe\Project\Controllers;

use Tribe\Project\Components\Handler;

/**
 * Class Controller
 *
 * @package Tribe\Project\Controllers
 */
class Controller {

	/**
	 * @var Handler
	 */
	protected $handler;

	/**
	 * Controller constructor.
	 *
	 * @param Handler $handler
	 */
	public function __construct( Handler $handler ) {
		$this->handler = $handler;
	}

	/**
	 * @param $component
	 * @param $args
	 */
	protected function render_component( $component, $args ) {
		$this->handler->render_component( $component, $args );
	}
}
