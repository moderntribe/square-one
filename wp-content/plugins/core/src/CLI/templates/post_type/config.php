<?php

namespace %s$1;

use Tribe\Libs\Post_Type\Post_Type_Config;

class Config extends Post_Type_Config {
	public function get_args() {
		return [
			'hierarchical'     => false,
			'enter_title_here' => __( '%s$2', 'tribe' ),
			'map_meta_cap'     => true,
			'supports'         => [ 'title', 'editor' ],
			'capability_type'  => 'post', // to use default WP caps
		];
	}

	public function get_labels() {
		return [
			'singular' => __( '%s$3', 'tribe' ),
			'plural'   => __( '%s$4', 'tribe' ),
			'slug'     => __( '%s$5', 'tribe' ),
		];
	}

}