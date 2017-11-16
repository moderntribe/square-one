<?php

namespace Tribe\Project\CLI;

class Settings_Generator extends Command {

	public function description() {
		return __( 'A generated CLI command.', 'tribe' );
	}

	public function command() {
		return 'settings_generator';
	}

	public function arguments() {
		return [
			[
				'type'        => 'positional',
				'name'        => 'settings_generator',
				'optional'    => true,
				'description' => 'The name of the settings_generator',
			],
		];
	}

	public function run_command( $args, $assoc_args ) {
		$this->slug       = $this->sanitize_slug( $args );
		$this->class_name = $this->ucwords( $this->slug );
	}

}
