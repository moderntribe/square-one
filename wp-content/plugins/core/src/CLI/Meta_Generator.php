<?php

namespace Tribe\Project\CLI;

class Meta_Generator extends Command {
	use File_System;

	public function description() {
		return __( 'Generates object meta.', 'tribe' );
	}

	public function callback() {
		return [ $this, 'meta' ];
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

	public function meta( $args, $assoc_args ) {
		print_r($assoc_args);
	}
}
