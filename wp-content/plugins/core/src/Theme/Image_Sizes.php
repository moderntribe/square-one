<?php


namespace Tribe\Project\Theme;


class Image_Sizes {
	const CORE_FULL      = 'core-full';
	const CORE_MOBILE    = 'core-mobile';
	const SOCIAL_SHARE   = 'social-share';
	const COMPONENT_CARD = 'component-card';
	const IMAGE_1X1      = 'image-1x1';
	const IMAGE_16X11    = 'image-16x11';

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
		],
		self::IMAGE_1X1 => [
			'width' => 600,
			'height' => 600,
			'crop' => true,
		],
		self::IMAGE_16X11 => [
			'width' => 800,
			'height' => 550,
			'crop' => true,
		],
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
