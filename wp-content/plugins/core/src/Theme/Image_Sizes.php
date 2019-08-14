<?php


namespace Tribe\Project\Theme;


class Image_Sizes {
	const CORE_FULL             = 'core-full';
	const CORE_MOBILE           = 'core-mobile';
	const SOCIAL_SHARE          = 'social-share';
	const COMPONENT_CARD        = 'component-card';
	const SQUARE_LARGE          = 'square-large';
	const SQUARE_MEDIUM         = 'square-med';
	const SQUARE_SMALL          = 'square-sm';
	const SQUARE_PLACEHOLDER    = 'square-placeholder';
	const CORE_16X9_LARGE       = '16x9-lg';
	const CORE_16X9_MEDIUM      = '16x9-med';
	const CORE_16X9_SMALL       = '16x9-sm';
	const CORE_16X9_PLACEHOLDER = '16x9-placeholder';

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
		self::SQUARE_LARGE => [
			'width'  => 900,
			'height' => 900,
			'crop'   => true,
		],
		self::SQUARE_MEDIUM => [
			'width'  => 600,
			'height' => 600,
			'crop'   => true,
		],
		self::SQUARE_SMALL => [
			'width'  => 300,
			'height' => 300,
			'crop'   => true,
		],
		self::SQUARE_PLACEHOLDER => [
			'width'  => 100,
			'height' => 100,
			'crop'   => true,
		],
		self::CORE_16X9_LARGE => [
			'width'  => 1600,
			'height' => 900,
			'crop'   => true,
		],
		self::CORE_16X9_MEDIUM => [
			'width'  => 800,
			'height' => 450,
			'crop'   => true,
		],
		self::CORE_16X9_SMALL => [
			'width'  => 400,
			'height' => 225,
			'crop'   => true,
		],
		self::CORE_16X9_PLACEHOLDER => [
			'width'  => 160,
			'height' => 90,
			'crop'   => true,
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
