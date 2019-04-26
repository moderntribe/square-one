<?php

namespace Tribe\Tests\Post_Types\Test_CPT;

use Tribe\Libs\Post_Type\Post_Type_Config;

class Config extends Post_Type_Config {
	public function get_args() {
		$args = [
			'hierarchical'     => false,
			'enter_title_here' => __( 'This is a test post type...', 'tribe' ),
			'map_meta_cap'     => true,
			'supports'         => [ 'title' ],
			//'capability_type'  => $this->post_type(), // for custom caps
			'capability_type'  => 'post', // to use default WP caps
			'menu_icon'        => 'dashicons-cloud',
		];

		return apply_filters( 'tribe_test_cpt_args', $args );
	}

	public function get_labels() {
		$labels = [
			'singular' => __( 'Test CPT', 'tribe' ),
			'plural'   => __( 'Test CPT', 'tribe' ),
			'slug'     => __( 'Test CPT', 'tribe' ),
		];

		return apply_filters( 'tribe_test_cpt_labels', $labels );
	}

}
