<?php


namespace Tribe\Project\Taxonomies\Example;


use Tribe\Project\Taxonomies\Taxonomy_Config;

class Config extends Taxonomy_Config {

	protected $version = 1;

	/**
	 * Arguments to pass when registering the taxonomy.
	 *
	 * @see register_extended_taxonomy() for accepted args.
	 * @return array
	 */
	public function get_args() {
		return [
			'hierarchical' => false,
			'exclusive' => true,
			'meta_box' => 'radio',
		];
	}

	public function get_labels() {
		return [
			'singular' => __( 'Example', 'tribe' ),
			'plural' => __( 'Examples', 'tribe' ),
			'slug' => __( 'examples', 'tribe' ),
		];
	}

	public function default_terms() {
		return [
			'first' => __( 'First Default Term', 'tribe' ),
			'second' => __( 'Second Default Term', 'tribe' ),
		];
	}
}
