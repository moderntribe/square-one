<?php

namespace Tribe\Project\Templates\Components;

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
class Slider extends Context {
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

	protected $path = __DIR__ . '/slider.twig';

	protected $properties = [
		self::SHOW_CAROUSEL     => [
			self::DEFAULT => true,
		],
		self::SHOW_ARROWS       => [
			self::DEFAULT => true,
		],
		self::SHOW_PAGINATION   => [
			self::DEFAULT => false,
		],
		self::SLIDES            => [
			self::DEFAULT => [],
		],
		self::THUMBNAILS        => [
			self::DEFAULT => [],
		],
		self::CONTAINER_CLASSES => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-slider' ],
		],
		self::MAIN_CLASSES      => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [ 'c-slider__main', 'swiper-container' ],
		],
		self::WRAPPER_CLASSES   => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-slider__wrapper', 'swiper-wrapper' ],
		],
		self::SLIDE_CLASSES     => [
			self::DEFAULT       => [],
			self::MERGE_CLASSES => [ 'c-slider__slide', 'swiper-slide' ],
		],
		self::MAIN_ATTRS        => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [ 'data-js' => 'c-slider' ],
		],
		self::CAROUSEL_ATTRS    => [
			self::DEFAULT          => [],
			self::MERGE_ATTRIBUTES => [ 'data-js' => 'c-slider-carousel' ],
		],
	];

	public function get_data(): array {
		if ( $this->show_carousel ) {
			$this->properties[ self::MAIN_CLASSES ][ self::MERGE_CLASSES ][] = 'c-slider__main--has-carousel';
		}
		if ( $this->show_arrows ) {
			$this->properties[ self::MAIN_CLASSES ][ self::MERGE_CLASSES ][] = 'c-slider__main--has-arrows';
		}
		if ( $this->show_pagination ) {
			$this->properties[ self::MAIN_CLASSES ][ self::MERGE_CLASSES ][] = 'c-slider__main--has-pagination';
		}

		return parent::get_data();
	}
}
