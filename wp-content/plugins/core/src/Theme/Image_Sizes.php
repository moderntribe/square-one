<?php


namespace Tribe\Project\Theme;


class Image_Sizes {
	private $sizes = [
		'core-full'    => [
			'width'  => 1600,
			'height' => 0,
			'crop'   => true,
		],
		'core-mobile'  => [
			'width'  => 1152,
			'height' => 0,
			'crop'   => true,
		],
		'social-share' => [
			'width'  => 1200,
			'height' => 630,
			'crop'   => true,
		],
	];

	private $opengraph_image_size = 'social-share';

	public function hook() {
		add_action( 'after_setup_theme', [ $this, 'register_sizes' ], 10, 0 );
		add_filter( 'wpseo_opengraph_image_size', [ $this, 'customize_wpseo_image_size' ], 10, 1 );
	}

	public function register_sizes() {
		foreach ( $this->sizes as $key => $attributes ) {
			add_image_size( $key, $attributes[ 'width' ], $attributes[ 'height' ], $attributes[ 'crop' ] );
		}
	}

	public function customize_wpseo_image_size( $size ) {
		return $this->opengraph_image_size;
	}
}
