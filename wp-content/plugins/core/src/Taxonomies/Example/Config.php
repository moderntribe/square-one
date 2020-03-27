<?php


namespace Tribe\Project\Taxonomies\Example;


use Tribe\Libs\Taxonomy\Taxonomy_Config;
use Tribe\Project\Post_Types\Sample\Sample;

class Config extends Taxonomy_Config {
	protected $taxonomy = Example::NAME;
	protected $post_types = [
		Sample::NAME,
	];

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
