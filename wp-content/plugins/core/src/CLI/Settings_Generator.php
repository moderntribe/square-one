<?php

namespace Tribe\Project\CLI;

class Settings_Generator extends Generator_Command {

	private $slug       = '';
	private $class_name = '';
	private $namespace  = '';
	private $assoc_args = [];

	public function description() {
		return __( 'Generate a settings page.', 'tribe' );
	}

	public function command() {
		return 'generate settings';
	}

	public function arguments() {
		return [
			[
				'type'        => 'positional',
				'name'        => 'settings',
				'optional'    => false,
				'description' => 'The name of the settings page.',
			],
		];
	}

	public function run_command( $args, $assoc_args ) {
		$this->slug       = $this->sanitize_slug( $args );
		$this->class_name = $this->ucwords( $this->slug );
		$this->namespace  = 'Tribe\Project\Settings';

		$this->create_settings_file();

		$this->update_service_provider();
	}

	private function create_settings_file() {
		$new_settings = $this->src_path . 'Settings/' . $this->ucwords( $this->slug ) . '.php';
		$this->file_system->write_file( $new_settings, $this->get_settings_file_contents() );
	}

	private function get_settings_file_contents() {
		$settings_file = $this->file_system->get_file( $this->templates_path . 'settings/settings.php' );

		return sprintf(
			$settings_file,
			$this->class_name,
			str_replace( '_', ' ', $this->class_name ),
			$this->slug
		);
	}

	protected function update_service_provider() {
		$service_provider = $this->src_path . 'Service_Providers/Settings_Provider.php';

		// Add class to pimple container.
		$container_partial_file = $this->file_system->get_file( $this->templates_path . 'settings/container_partial.php' );
		$container_partial = sprintf( $container_partial_file, $this->slug, $this->class_name );
		$this->file_system->insert_into_existing_file( $service_provider, $container_partial, '}, 0, 0 );' );

		$method = "\t\t\$this->{$this->slug}( \$container );" . PHP_EOL;
		$this->file_system->insert_into_existing_file( $service_provider, $method, 'public function register(' );
	}

}
