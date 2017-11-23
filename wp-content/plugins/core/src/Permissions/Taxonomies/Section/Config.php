<?php


namespace Tribe\Project\Permissions\Taxonomies\Section;

use Tribe\Libs\Taxonomy\Taxonomy_Config;

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
			'meta_box'     => false,
			'show_in_menu' => false,
			'capabilities' => [
				'manage_terms' => 'manage_sections',
				'edit_terms'   => 'edit_sections',
				'delete_terms' => 'delete_sections',
				'assign_terms' => 'assign_sections',
			],
		];
	}

	public function get_labels() {
		return [
			'singular' => __( 'Section', 'tribe' ),
			'plural'   => __( 'Sections', 'tribe' ),
			'slug'     => __( 'sections', 'tribe' ),
		];
	}

	public function default_terms() {
		return [
			Section::DEFAULT => _x( 'Home', 'default section term', 'tribe' ),
		];
	}

}
