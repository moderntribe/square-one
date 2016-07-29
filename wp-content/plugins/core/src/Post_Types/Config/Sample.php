<?php


namespace Tribe\Project\Post_Types\Config;

use Tribe\Libs\Post_Type\Post_Type_Config;

class Sample extends Post_Type_Config {
	public function get_args() {
		return [
			'hierarchical'     => false,
			'enter_title_here' => __( 'Sample', 'tribe' ),
			'map_meta_cap'     => true,
			'supports'         => [ 'title', 'editor' ],
			'capability_type'  => $this->post_type(),
		];
	}

	public function get_labels() {
		return [
			'singular' => __( 'Sample', 'tribe' ),
			'plural'   => __( 'Samples', 'tribe' ),
			'slug'     => __( 'samples', 'tribe' ),
		];
	}

}
