<?php

namespace Tribe\Project\CLI;

class Meta_Generator extends Generator_Command {

	public function description() {
		return __( 'Generates object meta.', 'tribe' );
	}

	public function command() {
		return 'meta';
	}

	public function arguments() {
		return [
			[
				'type'        => 'optional',
				'name'        => 'field',
				'optional'    => true,
			],
		];
	}

	public function run_command( $args, $assoc_args ) {
		print_r($assoc_args);
	}
}
