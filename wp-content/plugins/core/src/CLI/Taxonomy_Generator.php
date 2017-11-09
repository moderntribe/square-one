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
				'description' => 'The name of the Taxonomy.',
			],
			[
				'type'        => 'generic',
				'name'        => 'post_types',
				'optional'    => true,
				'description' => 'Comma seperated list of post types to register this taxonomy to.',
			],
			[
				'type'        => 'flag',
				'name'        => 'config',
				'optional'    => true,
				'description' => 'Whether or not to create a config file by default. Defaults to true, pass --no-config if you don\'t need one.',
				'default'     => true,
			],
			[
				'type'        => 'generic',
				'name'        => 'single',
				'optional'    => true,
				'description' => 'Singular taxonomy.',
			],
			[
				'type'        => 'generic',
				'name'        => 'plural',
				'optional'    => true,
				'description' => 'Plural taxonomy.',
			],
		];
	}

	public function taxonomy( $args, $assoc_args ) {
		$this->setup( $args, $assoc_args );

		$this->create_taxonomy_directory();
	}

	protected function setup( $args, $assoc_args ) {
		$this->slug = $this->sanitize_slug( $args );

		$this->class_name = ucfirst( $this->slug );
		$this->namespace  = 'Tribe\Project\Taxonomies\\' . $this->class_name;

		$this->assoc_args = $this->parse_assoc_args( $assoc_args );
	}

	private function sanitize_slug( $args ) {
		list( $slug ) = $args;

		return sanitize_title( $slug );
	}

	private function parse_assoc_args( $assoc_args ) {
		$defaults = [
			'single'     => ucfirst( $this->slug ),
			'plural'     => ucfirst( $this->slug ) . 's',
			'config'     => true,
			'post_types' => $this->get_post_types( $assoc_args ),
		];

		return wp_parse_args( $assoc_args, $defaults );
	}

	private function get_post_types( $assoc_args ) {
		if ( ! isset( $assoc_args['post_types'] ) ) {
			return self::POST_TYPES;
		}

		return explode( ',', $assoc_args['post_types'] );
	}

	private function create_taxonomy_directory() {
		$directory = trailingslashit( dirname( __DIR__ ) ) . 'Taxonomies/' . ucfirst( $this->slug );
		$this->taxonomy_directory = $directory;
		$this->create_directory( $directory );
	}

}