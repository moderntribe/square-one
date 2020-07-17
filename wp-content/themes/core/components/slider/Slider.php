<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Components\Component;

/**
 * Class Slider
 *
 * @property bool     $show_carousel
 * @property bool     $show_arrows
 * @property bool     $show_pagination
 * @property string[] $slides
 * @property string[] $thumbnails
 * @property string[] $container_classes
 * @property string[] $main_classes
 * @property string[] $wrapper_classes
 * @property string[] $slide_classes
 * @property string[] $main_attrs
 * @property string[] $carousel_attrs
 */
class Slider extends Component {

	public const SHOW_CAROUSEL     = 'show_carousel';
	public const SHOW_ARROWS       = 'show_arrows';
	public const SHOW_PAGINATION   = 'show_pagination';
	public const SLIDES            = 'slides';
	public const THUMBNAILS        = 'thumbnails';
	public const CONTAINER_CLASSES = 'container_classes';
	public const MAIN_CLASSES      = 'main_classes';
	public const WRAPPER_CLASSES   = 'wrapper_classes';
	public const SLIDE_CLASSES     = 'slide_classes';
	public const MAIN_ATTRS        = 'main_attrs';
	public const CAROUSEL_ATTRS    = 'carousel_attrs';

	protected function defaults(): array {
		return [
			self::SHOW_CAROUSEL     => true,
			self::SHOW_ARROWS       => true,
			self::SHOW_PAGINATION   => false,
			self::SLIDES            => [],
			self::THUMBNAILS        => [],
			self::CONTAINER_CLASSES => [ 'c-slider' ],
			self::MAIN_CLASSES      => [ 'c-slider__main', 'swiper-container' ],
			self::WRAPPER_CLASSES   => [ 'c-slider__wrapper', 'swiper-wrapper' ],
			self::SLIDE_CLASSES     => [ 'c-slider__slide', 'swiper-slide' ],
			self::MAIN_ATTRS        => [ 'data-js' => 'c-slider' ],
			self::CAROUSEL_ATTRS    => [ 'data-js' => 'c-slider-carousel' ],
		];
	}


	public function init(): array {
		if ( $this->data[ self::SHOW_CAROUSEL ] ) {
			$this->data[ self::MAIN_CLASSES ][] = 'c-slider__main--has-carousel';
		}
		if ( $this->data[ self::SHOW_ARROWS ] ) {
			$this->data[ self::MAIN_CLASSES ][] = 'c-slider__main--has-arrows';
		}
		if ( $this->data[ self::SHOW_PAGINATION ] ) {
			$this->data[ self::MAIN_CLASSES ][] = 'c-slider__main--has-pagination';
		}
	}
}
