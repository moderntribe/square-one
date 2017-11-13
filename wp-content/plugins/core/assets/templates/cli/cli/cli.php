<?php

namespace Tribe\Project\CLI;

class %1$s extends Square_One_Command {
	use File_System;

	public function description() {
		return __( '%2$s', 'tribe' );
	}

	public function command() {
		return '%3$s';
	}

	public function arguments() {
		return [
			[
				'type'        => 'positional',
				'name'        => '%3$s',
				'optional'    => true,
				'description' => 'The name of the %3$s',
			],
		];
	}

	public function run_command( $args, $assoc_args ) {
		$this->slug       = $this->sanitize_slug( $args );
		$this->class_name = $this->ucwords( $this->slug );
	}

}
