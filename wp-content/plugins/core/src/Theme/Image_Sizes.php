<?php


namespace Tribe\Project\Theme;


class Image_Sizes {
	const CORE_FULL      = 'core-full';
	const CORE_MOBILE    = 'core-mobile';
	const SOCIAL_SHARE   = 'social-share';
	const COMPONENT_CARD = 'component-card';

	private $sizes = [
		self::CORE_FULL    => [
			'width'  => 1600,
			'height' => 0,
			'crop'   => true,
		],
		self::CORE_MOBILE  => [
			'width'  => 1152,
			'height' => 0,
			'crop'   => true,
		],
		self::SOCIAL_SHARE => [
			'width'  => 1200,
			'height' => 630,
			'crop'   => true,
		],
		self::COMPONENT_CARD => [
			'width'  => 600,
			'height' => 400,
			'crop'   => true,
		]
	];

	private $opengraph_image_size = self::SOCIAL_SHARE;

	/**
	 * @return void
	 * @action after_setup_theme
	 */
	public function register_sizes() {
		foreach ( $this->sizes as $key => $attributes ) {
			add_image_size( $key, $attributes[ 'width' ], $attributes[ 'height' ], $attributes[ 'crop' ] );
		}
	}

	/**
	 * @param $size
	 * @return string
	 * @filter wpseo_opengraph_image_size
	 */
	public function customize_wpseo_image_size( $size ) {
		return $this->opengraph_image_size;
	}
}
