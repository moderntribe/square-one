<?php

namespace Tribe\Project\CLI;

class CPT_Generator extends Generator_Command {
	private $cpt_directory;
	private $slug;
	private $class_name;
	private $namespace;
	private $assoc_args;

	public function description() {
		return __( 'Generates a CPT.', 'tribe' );
	}

	public function command() {
		return 'generate cpt';
	}

	public function arguments() {
		return [
			[
				'type'        => 'positional',
				'name'        => 'cpt',
				'optional'    => false,
				'description' => __( 'The name of the CPT.', 'tribe' ),
			],
			[
				'type'        => 'assoc',
				'name'        => 'single',
				'optional'    => true,
				'description' => __( 'Singular CPT.', 'tribe' ),
			],
			[
				'type'        => 'assoc',
				'name'        => 'plural',
				'optional'    => true,
				'description' => __( 'Plural CPT.', 'tribe' ),
			],
			[
				'type'        => 'flag',
				'name'        => 'config',
				'optional'    => true,
				'description' => __( 'Whether or not to create a config file by default. Defaults to true, pass --no-config if you don\'t need one.', 'tribe' ),
				'default'     => true,
			],
		];
	}

	public function run_command( $args, $assoc_args ) {
		// Validate the slug.
		$this->slug       = $this->sanitize_slug( $args );
		$this->class_name = $this->ucwords( $this->slug );
		$this->namespace  = 'Tribe\Project\Post_Types\\' . $this->class_name;
		// Set up associate args with some defaults.
		$this->assoc_args = $this->parse_assoc_args( $assoc_args );

		// Create dir.
		$this->create_cpt_directory();

		// Create post object.
		$this->new_post_object_class();

		// Create service provider.
		$this->new_service_provider_file();

		// Update Core.
		$this->update_core();

		\WP_CLI::success( 'Way to go! ' . \WP_CLI::colorize( "%W{$this->slug}%n" ) . ' post type has been created' );
	}

	private function create_cpt_directory() {
		$new_directory = $this->src_path . 'Post_Types/' . $this->ucwords( $this->slug );
		$this->file_system->create_directory( $new_directory );
		$this->cpt_directory = $new_directory;
	}

	private function parse_assoc_args( $assoc_args ) {
		$defaults = [
			'single' => $this->ucwords( $this->slug ),
			'plural' => $this->ucwords( $this->slug ) . 's',
			'config' => true,
		];

		return wp_parse_args( $assoc_args, $defaults );
	}

	private function new_post_object_class() {
		$this->new_post_object_class_file();
		if ( $this->assoc_args['config'] ) {
			$this->new_post_object_config_file();
		}
	}

	private function new_service_provider_file() {
		$new_service_provider         = trailingslashit( dirname( __DIR__, 1 ) ) . 'Service_Providers/Post_Types/' . $this->ucwords( $this->slug ) . '_Service_Provider.php';
		$this->service_provider_class = $this->ucwords( $this->slug );
		$this->file_system->write_file( $new_service_provider, $this->get_service_provider_contents() );
	}

	private function update_core() {
		$core_file = $this->src_path . 'Core.php';

		$new_service_provider_registration   = "\t\t" . '$this->container->register( new ' . $this->class_name . '_Service_Provider() );' . PHP_EOL;
		$below_service_provider_registration = 'private function load_post_type_providers() {';

		$below_use = 'use Tribe\Project\Service_Providers\Post_Types\Post_Service_Provider';
		$use       = 'use Tribe\Project\Service_Providers\Post_Types\\' . $this->class_name . '_Service_Provider;' . PHP_EOL;

		$this->file_system->insert_into_existing_file( $core_file, $new_service_provider_registration, $below_service_provider_registration );
		$this->file_system->insert_into_existing_file( $core_file, $use, $below_use );
	}

	private function new_post_object_class_file() {
		$class_file = trailingslashit( $this->cpt_directory ) . $this->ucwords( $this->slug ) . '.php';
		$this->file_system->write_file( $class_file, $this->get_class_contents() );
	}

	private function new_post_object_config_file() {
		$config = trailingslashit( $this->cpt_directory ) . 'Config.php';
		$this->file_system->write_file( $config, $this->get_config_contents() );
	}

	// ------------templates---------------///
	private function get_class_contents() {

		$post_type_file = $this->file_system->get_file( $this->templates_path . 'post_type/post_type.php' );

		return sprintf(
			$post_type_file,
			$this->namespace,
			$this->class_name,
			$this->slug
		);

	}

	private function get_config_contents() {

		$config_file = $this->file_system->get_file( $this->templates_path . 'post_type/config.php' );

		return sprintf(
			$config_file,
			$this->namespace,
			$this->class_name,
			$this->assoc_args['single'],
			$this->assoc_args['plural'],
			$this->slug
		);

	}

	private function get_service_provider_contents() {

		$service_provider_file = $this->file_system->get_file( $this->templates_path . 'post_type/service_provider.php' );

		return sprintf(
			$service_provider_file,
			$this->class_name
		);

	}

}
