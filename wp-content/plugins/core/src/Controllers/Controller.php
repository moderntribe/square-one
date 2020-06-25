<?php

namespace Tribe\Project\Controllers;

use Tribe\Project\Components\Handler;

class Controller {

	/**
	 * @var Handler
	 */
	protected $handler;

	public function __construct( Handler $handler ) {
		$this->handler = $handler;
	}

	protected function render_component( $component, $args ) {
		$this->handler->render_component( $component, $args );
	}
}
