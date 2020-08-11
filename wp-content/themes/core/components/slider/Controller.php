<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\slider;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {
	/**
	 * @var string[]
	 */
	private $classes;
	/**
	 * @var string[]
	 */
	private $main_classes;
	/**
	 * @var string[]
	 */
	private $main_attrs;
	/**
	 * @var string[]
	 */
	private $wrapper_classes;
	/**
	 * @var string[]
	 */
	private $slide_classes;
	/**
	 * @var array
	 */
	public $slides;
	/**
	 * @var bool
	 */
	public $show_arrows;
	/**
	 * @var bool
	 */
	public $show_pagination;
	/**
	 * @var bool
	 */
	public $show_carousel;
	/**
	 * @var string[]
	 */
	private $carousel_attrs;
	/**
	 * @var array
	 */
	public $carousel_slides;

	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->defaults() );

		foreach ( $this->required() as $key => $value ) {
			$args[$key] = array_merge( $args[$key], $value );
		}

		$this->classes         = (array) $args['classes'];
		$this->main_classes    = (array) $args['main_classes'];
		$this->main_attrs      = (array) $args['main_attrs'];
		$this->wrapper_classes = (array) $args['wrapper_classes'];
		$this->slide_classes   = (array) $args['slide_classes'];
		$this->slides          = (array) $args['slides'];
		$this->show_arrows     = $args['show_arrows'];
		$this->show_pagination = $args['show_pagination'];
		$this->show_carousel   = $args['show_carousel'];
		$this->carousel_attrs  = (array) $args['carousel_attrs'];
		$this->carousel_slides = (array) $args['carousel_slides'];

		$this->init_classes();
	}

	protected function defaults(): array {
		return [
			'classes'         => [],
			'main_classes'    => [],
			'main_attrs'      => [],
			'wrapper_classes' => [],
			'slide_classes'   => [],
			'slides'          => [],
			'show_arrows'     => true,
			'show_pagination' => false,
			'show_carousel'   => false,
			'carousel_attrs'  => [],
			'carousel_slides' => [],
		];
	}

	protected function required(): array {
		return [
			'classes'         => [ 'c-slider' ],
			'main_classes'    => [ 'c-slider__main', 'swiper-container' ],
			'main_attrs'      => [ 'data-js' => 'c-slider' ],
			'wrapper_classes' => [ 'c-slider__wrapper', 'swiper-wrapper' ],
			'slide_classes'   => [ 'c-slider__slide', 'swiper-slide' ],
			'carousel_attrs'  => [ 'data-js' => 'c-slider-carousel' ],
		];
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
