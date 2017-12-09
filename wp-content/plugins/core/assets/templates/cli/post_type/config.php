<?php

namespace %1$s;

use Tribe\Libs\Post_Type\Post_Type_Config;

class Config extends Post_Type_Config {
	public function get_args() {
		return [
			'hierarchical'     => false,
			'enter_title_here' => __( '%2$s', 'tribe' ),
			'menu_icon'        => 'dashicons-warning',
			'map_meta_cap'     => true,
			'supports'         => [ 'title', 'editor', 'author', 'thumbnail', 'page-attributes', 'excerpt', 'revisions', ],
			'capability_type'  => 'post', // to use default WP caps
		];
	}

	public function get_labels() {
		return [
			'singular' => __( '%3$s', 'tribe' ),
			'plural'   => __( '%4$s', 'tribe' ),
			'slug'     => __( '%5$s', 'tribe' ),
		];
	}

}