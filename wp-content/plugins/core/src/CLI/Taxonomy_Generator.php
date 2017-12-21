<?php

namespace Tribe\Project\CLI;

use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Post\Post;

class Taxonomy_Generator extends Command {

	const POST_TYPES = [
		Post::NAME,
		Page::NAME,
	];

	protected $slug               = '';
	protected $class_name         = '';
	protected $namespace          = '';
	protected $taxonomy_directory = '';
	protected $assoc_args         = [];

	public function command() {
		return 'generate tax';
	}

	public function description() {
		return 'Generates a Taxonomy';
	}

	public function arguments() {
		return [
			[
				'type'        => 'positional',
				'name'        => 'taxonomy',
				'optional'    => false,
				'description' => __( 'The name of the Taxonomy.', 'tribe' ),
			],
			[
				'type'        => 'assoc',
				'name'        => 'post-types',
				'optional'    => true,
				'description' => __( 'Comma separated list of post types to register this taxonomy to.', 'tribe' ),
			],
			[
				'type'        => 'assoc',
				'name'        => 'single',
				'optional'    => true,
				'description' => __( 'Singular taxonomy.', 'tribe' ),
			],
			[
				'type'        => 'assoc',
				'name'        => 'plural',
				'optional'    => true,
				'description' => __( 'Plural taxonomy.', 'tribe' ),
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
		$this->setup( $args, $assoc_args );

		// Create directory.
		$this->create_taxonomy_directory();

		// Write file(s).
		$this->create_taxonomy_class();

		// Write Service Provider.
		$this->create_service_provider();

		// Update Core.
		$this->update_core();

		\WP_CLI::success( 'Way to go! ' . \WP_CLI::colorize( "%W{$this->slug}%n" ) . ' taxonomy has been created' );
	}

	protected function setup( $args, $assoc_args ) {
		$this->slug = $this->sanitize_slug( $args );

		$this->class_name = $this->ucwords( $this->slug );
		$this->namespace  = 'Tribe\Project\Taxonomies\\' . $this->class_name;

		$this->assoc_args = $this->parse_assoc_args( $assoc_args );
	}

	private function parse_assoc_args( $assoc_args ) {
		$defaults = [
			'single' => $this->ucwords( $this->slug ),
			'plural' => $this->ucwords( $this->slug ) . 's',
			'config' => true,
		];

		$assoc_args['post-types'] = $this->get_post_types( $assoc_args );

		return wp_parse_args( $assoc_args, $defaults );
	}

	private function get_post_types( $assoc_args ) {
		if ( ! isset( $assoc_args['post-types'] ) ) {
			return self::POST_TYPES;
		}

		$post_types = explode( ',', $assoc_args['post-types'] );
		foreach ( $post_types as $post_type ) {
			if ( ! post_type_exists( $post_type ) ) {
				\WP_CLI::error( 'Sorry...post type ' . $post_type . ' does not exist.' );
			}
		}

		return $post_types;
	}

	private function create_taxonomy_directory() {
		$directory                = trailingslashit( $this->src_path ) . 'Taxonomies/' . $this->ucwords( $this->slug );
		$this->taxonomy_directory = $directory;
		$this->file_system->create_directory( $directory );
	}

	private function create_taxonomy_class() {
		$this->new_taxonomy_class_file();
		if ( $this->assoc_args['config'] ) {
			$this->new_taxonomy_config_file();
		}
	}

	private function create_service_provider() {
		$service_provider_file = $this->src_path . 'Service_Providers/Taxonomies/' . $this->ucwords( $this->slug ) . '_Service_Provider.php';
		$this->file_system->write_file( $service_provider_file, $this->get_service_provider_contents() );
	}

	private function update_core() {
		$core_file = $this->src_path . 'Core.php';

		$new_service_provider_registration   = "\t\t" . '$this->container->register( new ' . $this->class_name . '_Service_Provider() );' . PHP_EOL;
		$below_service_provider_registration = 'private function load_taxonomy_providers() {';

		$below_use = 'use Tribe\Project\Service_Providers\Taxonomies\Category_Service_Provider';
		$use       = 'use Tribe\Project\Service_Providers\Taxonomies\\' . $this->class_name . '_Service_Provider;';

		$this->file_system->insert_into_existing_file( $core_file, $new_service_provider_registration, $below_service_provider_registration );
		$this->file_system->insert_into_existing_file( $core_file, $use, $below_use );
	}

	private function new_taxonomy_class_file() {
		$class_file = trailingslashit( $this->taxonomy_directory ) . $this->ucwords( $this->slug ) . '.php';
		$this->file_system->write_file( $class_file, $this->get_taxonomy_class_contents() );
	}

	private function new_taxonomy_config_file() {
		$config_file = trailingslashit( $this->taxonomy_directory ) . 'Config.php';
		$this->file_system->write_file( $config_file, $this->get_taxonomy_config_contents() );
	}

	private function get_taxonomy_class_contents() {

		$taxonomy_file = $this->file_system->get_file( $this->templates_path . 'taxonomies/taxonomy.php' );

		return sprintf(
			$taxonomy_file,
			$this->namespace,
			$this->class_name,
			$this->slug
		);
	}

	private function get_taxonomy_config_contents() {

		$config_file = $this->file_system->get_file( $this->templates_path . 'taxonomies/config.php' );

		return sprintf(
			$config_file,
			$this->namespace,
			$this->assoc_args['single'],
			$this->assoc_args['plural'],
			$this->slug
		);
	}

	private function get_service_provider_contents() {
		$post_types = $this->format_post_types();

		$service_provider = $this->file_system->get_file( $this->templates_path . 'taxonomies/service_provider.php' );

		return sprintf(
			$service_provider,
			$this->namespace,
			$this->class_name,
			$post_types
		);
	}

	private function format_post_types() {
		if ( empty( $this->assoc_args['post-types'] ) ) {
			return '';
		}

		$post_types = 'protected $post_types = [ ';
		foreach ( $this->assoc_args['post-types'] as $post_type ) {
			$post_types .= '\'' . $post_type . '\', ';
		}
		$post_types .= '];' . PHP_EOL;

		return $post_types;
	}

}