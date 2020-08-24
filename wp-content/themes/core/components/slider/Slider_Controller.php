<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\slider;

use Tribe\Libs\Utils\Markup_Utils;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Slider_Controller extends Abstract_Controller {
	public const CLASSES         = 'classes';
	public const MAIN_CLASSES    = 'main_classes';
	public const MAIN_ATTRS      = 'main_attrs';
	public const WRAPPER_CLASSES = 'wrapper_classes';
	public const SLIDE_CLASSES   = 'slide_classes';
	public const SLIDES          = 'slides';
	public const SHOW_ARROWS     = 'show_arrows';
	public const SHOW_PAGINATION = 'show_pagination';
	public const SHOW_CAROUSEL   = 'show_carousel';
	public const CAROUSEL_ATTRS  = 'carousel_attrs';
	public const CAROUSEL_SLIDES = 'carousel_slides';

	private array $classes;
	private array $main_classes;
	private array $main_attrs;
	private array $wrapper_classes;
	private array $slide_classes;
	private array $slides;
	private bool  $show_arrows;
	private bool  $show_pagination;
	private bool  $show_carousel;
	private array $carousel_attrs;
	private array $carousel_slides;

	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );

		$this->classes         = (array) $args[ self::CLASSES ];
		$this->main_classes    = (array) $args[ self::MAIN_CLASSES ];
		$this->main_attrs      = (array) $args[ self::MAIN_ATTRS ];
		$this->wrapper_classes = (array) $args[ self::WRAPPER_CLASSES ];
		$this->slide_classes   = (array) $args[ self::SLIDE_CLASSES ];
		$this->slides          = (array) $args[ self::SLIDES ];
		$this->show_arrows     = (bool) $args[ self::SHOW_ARROWS ];
		$this->show_pagination = (bool) $args[ self::SHOW_PAGINATION ];
		$this->show_carousel   = (bool) $args[ self::SHOW_CAROUSEL ];
		$this->carousel_attrs  = (array) $args[ self::CAROUSEL_ATTRS ];
		$this->carousel_slides = (array) $args[ self::CAROUSEL_SLIDES ];
	}

	protected function defaults(): array {
		return [
			self::CLASSES         => [],
			self::MAIN_CLASSES    => [],
			self::MAIN_ATTRS      => [],
			self::WRAPPER_CLASSES => [],
			self::SLIDE_CLASSES   => [],
			self::SLIDES          => [],
			self::SHOW_ARROWS     => true,
			self::SHOW_PAGINATION => false,
			self::SHOW_CAROUSEL   => false,
			self::CAROUSEL_ATTRS  => [],
			self::CAROUSEL_SLIDES => [],
		];
	}

	protected function required(): array {
		return [
			self::CLASSES         => [ 'c-slider' ],
			self::MAIN_CLASSES    => [ 'c-slider__main', 'swiper-container' ],
			self::MAIN_ATTRS      => [ 'data-js' => 'c-slider' ],
			self::WRAPPER_CLASSES => [ 'c-slider__wrapper', 'swiper-wrapper' ],
			self::SLIDE_CLASSES   => [ 'c-slider__slide', 'swiper-slide' ],
			self::CAROUSEL_ATTRS  => [ 'data-js' => 'c-slider-carousel' ],
		];
	}

	public function get_classes(): string {
		if ( $this->show_arrows ) {
			$this->classes[] = 'c-slider__main--has-arrows';
		}

		if ( $this->show_pagination ) {
			$this->classes[] = 'c-slider__main--has-pagination';
		}

		if ( $this->show_carousel ) {
			$this->classes[] = 'c-slider__main--has-carousel';
		}

		return Markup_Utils::class_attribute( $this->classes );
	}

	public function get_main_classes(): string {
		return Markup_Utils::class_attribute( $this->main_classes );
	}

	public function get_main_attrs(): string {
		return Markup_Utils::concat_attrs( $this->main_attrs );
	}

	public function get_wrapper_classes(): string {
		return Markup_Utils::class_attribute( $this->wrapper_classes );
	}

	public function get_slide_classes(): string {
		return Markup_Utils::class_attribute( $this->slide_classes );
	}

	public function get_carousel_attrs(): string {
		return Markup_Utils::concat_attrs( $this->carousel_attrs );
	}

	public function get_slides(): array {
		return $this->slides;
	}

	public function get_carousel_slides(): array {
		return $this->carousel_slides;
	}

	public function should_show_arrows(): bool {
		return $this->show_arrows;
	}

	public function should_show_pagination(): bool {
		return $this->show_pagination;
	}

	public function should_show_carousel(): bool {
		return $this->show_carousel;
	}
}
