<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\slider;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {
	private $classes;
	private $main_classes;
	private $main_attrs;
	private $wrapper_classes;
	private $slide_classes;
	public $slides;
	public $show_arrows;
	public $show_pagination;
	public $show_carousel;
	private $carousel_attrs;
	public $carousel_slides;

	public function __construct( array $args = [] ) {
		$this->classes         = array_merge( [ 'c-slider' ], (array) ( $args['classes'] ?? [] ) );
		$this->main_classes    = array_merge( [ 'c-slider__main', 'swiper-container' ], (array) ( $args['main_classes'] ?? [] ) );
		$this->main_attrs      = array_merge( [ 'data-js' => 'c-slider' ], (array) ( $args['main_attrs'] ?? [] ) );
		$this->wrapper_classes = array_merge( [ 'c-slider__wrapper', 'swiper-wrapper' ], (array) ( $args['wrapper_classes'] ?? [] ) );
		$this->slide_classes   = array_merge( [ 'c-slider__slide', 'swiper-slide' ], (array) ( $args['slide_classes'] ?? [] ) );
		$this->slides          = (array) ( $args['slides'] ?? [] );
		$this->show_arrows     = $args['show_arrows'] ?? true;
		$this->show_pagination = $args['show_pagination'] ?? false;
		$this->show_carousel   = $args['show_carousel'] ?? false;
		$this->carousel_attrs  = array_merge( [ 'data-js' => 'c-slider-carousel' ], (array) ( $args['carousel_attrs'] ?? [] ) );
		$this->carousel_slides = (array) ( $args['carousel_slides'] ?? [] );

		$this->init_classes();
	}

	public function init_classes() {
		if ( $this->show_arrows ) {
			$this->classes[] = 'c-slider__main--has-arrows';
		}
		if ( $this->show_pagination ) {
			$this->classes[] = 'c-slider__main--has-pagination';
		}
		if ( $this->show_carousel ) {
			$this->classes[] = 'c-slider__main--has-carousel';
		}
	}

	public function classes(): string {
		return Markup_Utils::class_attribute( $this->classes );
	}

	public function main_classes(): string {
		return Markup_Utils::class_attribute( $this->main_classes );
	}

	public function main_attributes(): string {
		return Markup_Utils::concat_attrs( $this->main_attrs );
	}

	public function wrapper_classes(): string {
		return Markup_Utils::class_attribute( $this->wrapper_classes );
	}

	public function slide_classes(): string {
		return Markup_Utils::class_attribute( $this->slide_classes );
	}

	public function carousel_attributes(): string {
		return Markup_Utils::concat_attrs( $this->carousel_attrs );
	}
}
