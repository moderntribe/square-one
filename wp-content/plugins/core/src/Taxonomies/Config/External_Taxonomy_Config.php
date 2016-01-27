<?php


namespace Tribe\Project\Taxonomies\Config;


use Tribe\Libs\Taxonomy\Taxonomy_Config;

/**
 * Class External_Taxonomy_Config
 *
 * Use for registering a 3rd-party taxonomy that we
 * want to extend to work with other post types. E.g.,
 * to apply the Category taxonomy to a custom post type.
 */
class External_Taxonomy_Config extends Taxonomy_Config {
	public function get_args() {
		return []; // already registered
	}

	public function get_labels() {
		return []; // already registered
	}
}