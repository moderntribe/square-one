<?php

namespace Tribe\Project\Post_Types\Config;

/**
 * Class External_Post_Type_Config
 *
 * A placeholder for 3rd-party post types.
 */
class External_Post_Type_Config extends \Tribe\Libs\Post_Type\Post_Type_Config {

	public function register() {
		// do nothing, it's already registered
	}

	public function get_args() {
		return [ ];
	}

	public function get_labels() {
		return [ ];
	}
}