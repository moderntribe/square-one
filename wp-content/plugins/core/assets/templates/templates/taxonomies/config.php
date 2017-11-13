<?php

namespace %1$s;

use Tribe\Libs\Taxonomy\Taxonomy_Config;

class Config extends Taxonomy_Config {
	public function get_args() {
		return [
			'hierarchical' => false,
		];
	}

	public function get_labels() {
		return [
			'singular' => __( '%2$s', 'tribe' ),
					'plural'   => __( '%3$s', 'tribe' ),
					'slug'     => __( '%4$s', 'tribe' ),
				];
			}

	public function default_terms() {
		return [
		];
	}
}