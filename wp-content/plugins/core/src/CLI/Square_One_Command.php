<?php

namespace Tribe\Project\CLI;
use WP_CLI;

abstract class Square_One_Command extends \WP_CLI_Command {

	public function register() {
		if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
			return;
		}

		WP_CLI::add_command( 's1 ' . $this->command(), $this->callback(), [
			'shortdesc' => $this->description(),
			'synopsis'  => $this->arguments(),
		] );
	}

	abstract protected function command();
	abstract protected function callback();
	abstract protected function description();
	abstract protected function arguments();
}