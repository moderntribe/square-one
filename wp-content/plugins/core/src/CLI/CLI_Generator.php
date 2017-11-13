<?php

namespace Tribe\Project\CLI;

class CLI_Generator extends Square_One_Command {
	use File_System;

	protected $slug = '';
	protected $class_name = '';
	protected $namespace = '';
	protected $assoc_args = [];

	public function command() {
		return 'cli';
	}

	public function description() {
		return 'Generates a new CLI command';
	}

	public function callback() {
		return [ $this, 'cli' ];
	}

	public function arguments() {
		return [
			[
				'type'        => 'positional',
				'name'        => 'command',
				'optional'    => false,
				'description' => __( 'The command slug.', 'tribe' ),
			],
			[
				'type'        => 'optional',
				'name'        => 'description',
				'optional'    => true,
				'description' => __( 'Command description.', 'tribe' ),
			],
		];
	}

	public function cli( $args, $assoc_args ) {
		$this->slug       = $this->sanitize_slug( $args );
		$this->class_name = $this->ucwords( $this->slug );
		$this->namespace  = 'Tribe\Project\CLI\\' . $this->class_name;
		// Set up associate args with some defaults.
		$this->assoc_args = $this->parse_assoc_args( $assoc_args );

		$this->create_cli_file();

		$this->update_service_provider();
	}

	protected function parse_assoc_args( $assoc_args ) {
		$defaults = [
			'description' => 'A generated CLI command.',
		];

		return wp_parse_args( $assoc_args, $defaults );
	}

	protected function create_cli_file() {
		$new_cli_command = trailingslashit( dirname( __DIR__, 1 ) ) . 'CLI/' . $this->ucwords( $this->slug ) . '.php';
		$this->write_file( $new_cli_command, $this->get_cli_file_contents() );
	}

	protected function get_cli_file_contents() {
		$command_file = file_get_contents( trailingslashit( dirname( __DIR__, 1 ) ) . 'CLI/templates/cli/cli.php' );

		return sprintf(
			$command_file,
			$this->class_name,
			$this->assoc_args['description'],
			$this->slug
		);
	}

	protected function update_service_provider() {
		$cli_service_provider = trailingslashit( dirname( __DIR__, 1 ) ) . 'Service_Providers/CLI_Provider.php';

		// Use.
		$this->insert_into_existing_file($cli_service_provider, 'use ' . $this->namespace . ';' . PHP_EOL, 'use Tribe\Project\CLI\CLI_Generator;' );

		// Add class to pimple container.
		$container_partial_file = file_get_contents( trailingslashit( dirname( __DIR__, 1 ) ) . 'CLI/templates/cli/container_partial.php' );
		$container_partial = sprintf( $container_partial_file, $this->slug, $this->class_name );
		$this->insert_into_existing_file( $cli_service_provider, $container_partial, 'return new CLI_Generator();' );

		// Add to hook.
		$this->insert_into_existing_file( $cli_service_provider, "\t\t\t" . '$container[\'cli.' . $this->slug . '\']->register();' . PHP_EOL , '$container[\'cli.cli-generator\']->register();' );
	}
}
