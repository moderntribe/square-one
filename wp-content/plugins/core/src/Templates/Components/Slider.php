<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Twig\Twig_Template;

class Slider extends Twig_Template {

	const SHOW_CAROUSEL   = 'show_carousel';
	const SHOW_ARROWS     = 'show_arrows';
	const SLIDES          = 'slides';
	const THUMBNAILS      = 'thumbnails';
	const CLASSES         = 'classes';
	const MAIN_CLASSES    = 'main_classes';
	const WRAPPER_CLASSES = 'wrapper_classes';
	const SLIDE_CLASSES   = 'slide_classes';
	const MAIN_ATTRS      = 'main_attrs';

	protected $slider = [];

	public function __construct( $slider, $template, \Twig_Environment $twig = null ) {
		parent::__construct( $template, $twig );

		$this->slider = $slider;
	}

	public function get_data(): array {
		$data = [
			'slides'                      => $this->slider[ self::SLIDES ],
			'thumbnails'                  => $this->slider[ self::THUMBNAILS ],
			'show_carousel'               => $this->slider[ self::SHOW_CAROUSEL ],
			'show_arrows'                 => $this->slider[ self::SHOW_ARROWS ],
			'slider_classes'              => $this->get_slider_classes(),
			'slider_main_classes'         => $this->get_slider_main_classes(),
			'slider_main_wrapper_classes' => $this->get_slider_main_wrapper_classes(),
			'slider_main_slide_classes'   => $this->get_slider_main_slide_classes(),
			'slider_main_attrs'           => $this->get_slider_main_attrs(),
		];

		return $data;
	}

	protected function get_slider_classes(): string {
		$classes = [ 'c-slider' ];

		if ( ! empty( $this->slider[ self::CLASSES ] ) ) {
			$classes = array_merge( $classes, $this->slider[ self::CLASSES ] );
		}

		return implode( ' ', $classes );
	}

	protected function get_slider_main_classes(): string {
		$classes = [ 'c-slider__main', 'swiper-container' ];

		if ( ! empty( $this->slider[ self::MAIN_CLASSES ] ) ) {
			$classes = array_merge( $classes, $this->slider[ self::MAIN_CLASSES ] );
		}

		return implode( ' ', $classes );
	}

	protected function get_slider_main_slide_classes(): string {
		$classes = [ 'c-slider__slide', 'swiper-slide' ];

		if ( ! empty( $this->slider[ self::SLIDE_CLASSES ] ) ) {
			$classes = array_merge( $classes, $this->slider[ self::SLIDE_CLASSES ] );
		}

		return implode( ' ', $classes );
	}

	protected function get_slider_main_wrapper_classes(): string {
		$classes = [ 'c-slider__wrapper', 'swiper-wrapper' ];

		if ( ! empty( $this->slider[ self::WRAPPER_CLASSES ] ) ) {
			$classes = array_merge( $classes, $this->slider[ self::WRAPPER_CLASSES ] );
		}

		return implode( ' ', $classes );
	}

	protected function get_slider_main_attrs(): string {
		return 'data-js="c-slider"';
	}

	/**
	 * Get an instance of this controller bound to the correct data.
	 *
	 * @param        $slider
	 * @param string $template
	 *
	 * @return static
	 */
	public static function factory( $slider, $template = 'components/slider.twig' ) {
		return new static( $slider, $template );
	}
}