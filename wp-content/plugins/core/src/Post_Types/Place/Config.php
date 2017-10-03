<?php


namespace Tribe\Project\Post_Types\Place;

use Tribe\Libs\Post_Type\Post_Type_Config;

class Config extends Post_Type_Config {
	public function get_args() {
		return [
			'hierarchical'     => true,
			'enter_title_here' => __( 'Place', 'tribe' ),
			'map_meta_cap'     => true,
			'supports'         => [ 'title', 'editor', 'thumbnail' ],
			'capability_type'  => 'post', // to use default WP caps
		];
	}

	public function get_labels() {
		return [
			'singular' => __( 'Place', 'tribe' ),
			'plural'   => __( 'Places', 'tribe' ),
			'slug'     => __( 'places', 'tribe' ),
		];
	}

}
