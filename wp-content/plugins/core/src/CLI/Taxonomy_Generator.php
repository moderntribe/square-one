<?php

namespace Tribe\Project\CLI;

use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Post\Post;

class Taxonomy_Generator extends Square_One_Command {
	use File_System;

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
		return 'tax';
	}

	public function callback() {
		return [ $this, 'taxonomy' ];
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
				'type'        => 'optional',
				'name'        => 'post_types',
				'optional'    => true,
				'description' => __( 'Comma seperated list of post types to register this taxonomy to.', 'tribe' ),
			],
			[
				'type'        => 'flag',
				'name'        => 'config',
				'optional'    => true,
				'description' => __( 'Whether or not to create a config file by default. Defaults to true, pass --no-config if you don\'t need one.', 'tribe' ),
				'default'     => true,
			],
			[
				'type'        => 'optional',
				'name'        => 'single',
				'optional'    => true,
				'description' => __( 'Singular taxonomy.', 'tribe' ),
			],
			[
				'type'        => 'optional',
				'name'        => 'plural',
				'optional'    => true,
				'description' => __( 'Plural taxonomy.', 'tribe' ),
			],
		];
	}

	public function taxonomy( $args, $assoc_args ) {
		$this->setup( $args, $assoc_args );

		// Create directory.
		$this->create_taxonomy_directory();

		// Write file(s).
		$this->create_taxonomy_class();

		// Write Service Provider.
		$this->create_service_provider();

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
			'single'     => $this->ucwords( $this->slug ),
			'plural'     => $this->ucwords( $this->slug ) . 's',
			'config'     => true,
			'post_types' => $this->get_post_types( $assoc_args ),
		];

		return wp_parse_args( $assoc_args, $defaults );
	}

	private function get_post_types( $assoc_args ) {
		if ( ! isset( $assoc_args['post_types'] ) ) {
			return self::POST_TYPES;
		}

		$post_types = explode( ',', $assoc_args['post_types'] );
		foreach ( $post_types as $post_type ) {
			if ( ! post_type_exists( $post_type ) ) {
				\WP_CLI::error( 'Sorry...post type ' . $post_type . ' does not exist.' );
			}
		}

		return $post_types;
	}

	private function create_taxonomy_directory() {
		$directory = trailingslashit( dirname( __DIR__ ) ) . 'Taxonomies/' . $this->ucwords( $this->slug );
		$this->taxonomy_directory = $directory;
		$this->create_directory( $directory );
	}

	private function create_taxonomy_class() {
		$this->new_taxonomy_class_file();
		if ( $this->assoc_args['config'] ) {
			$this->new_taxonomy_config_file();
		}
	}

	private function create_service_provider() {
		$service_provider_file = trailingslashit( dirname( __DIR__, 1 ) ) . 'Service_Providers/Taxonomies/' . $this->ucwords( $this->slug ) . '_Service_Provider.php';
		$this->write_file( $service_provider_file, $this->get_service_provider_contents() );
	}

	private function new_taxonomy_class_file() {
		$class_file = trailingslashit( $this->taxonomy_directory ) . $this->ucwords( $this->slug ) . '.php';
		$this->write_file( $class_file, $this->get_taxonomy_class_contents() );
	}

	private function new_taxonomy_config_file() {
		$config_file = trailingslashit( $this->taxonomy_directory ) . 'Config.php';
		$this->write_file( $config_file, $this->get_taxonomy_config_contents() );
	}

	private function get_taxonomy_class_contents() {

		$taxonomy_file = file_get_contents( trailingslashit( dirname( __DIR__, 1 ) ) . 'CLI/templates/taxonomies/taxonomy.php' );

		return sprintf(
			$taxonomy_file,
			$this->namespace,
			$this->class_name,
			$this->slug
		);
	}

	private function get_taxonomy_config_contents() {

		$config_file = file_get_contents( trailingslashit( dirname( __DIR__, 1 ) ) . 'CLI/templates/taxonomies/config.php' );

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

		$service_provider = file_get_contents( trailingslashit( dirname( __DIR__, 1 ) ) . 'CLI/templates/taxonomies/service_provider.php' );
		return sprintf(
			$service_provider,
			$this->namespace,
			$this->class_name,
			$post_types
		);
	}

	private function format_post_types() {
		if ( empty( $this->assoc_args['post_types'] ) ) {
			return '';
		}

		$post_types = 'protected $post_types = [ ';
		foreach ( $this->assoc_args['post_types'] as $post_type ) {
			$post_types .= '\'' . $post_type . '\', ';
		}
		$post_types .= '];' . PHP_EOL;

		return $post_types;
	}

}