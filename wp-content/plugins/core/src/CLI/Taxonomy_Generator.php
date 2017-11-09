<?php

namespace Tribe\Project\CLI;

class Taxonomy_Generator extends Square_One_Command {
	use File_System;

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

	}
}