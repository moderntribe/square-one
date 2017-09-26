<?php

namespace Tribe\Project\CLI;

use Pimple\Container;

class Pimple_Dump extends \WP_CLI_Command {

	private $container;

	public function __construct( Container $container ) {
		$this->container = $container;
		parent::__construct();
	}

}