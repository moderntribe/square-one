<?php

namespace Tribe\Project\Templates\Components;

class Slider extends Component {

	protected $path = 'components/slider.twig';

	const SHOW_CAROUSEL   = 'show_carousel';
	const SHOW_ARROWS     = 'show_arrows';
	const SHOW_PAGINATION = 'show_pagination';
	const SLIDES          = 'slides';
	const THUMBNAILS      = 'thumbnails';
	const CLASSES         = 'container_classes';
	const MAIN_CLASSES    = 'main_classes';
	const WRAPPER_CLASSES = 'wrapper_classes';
	const SLIDE_CLASSES   = 'slide_classes';
	const MAIN_ATTRS      = 'main_attrs';
	const CAROUSEL_ATTRS  = 'carousel_attrs';

	protected function parse_options( array $options ): array {
		$defaults = [
			self::SHOW_CAROUSEL   => true,
			self::SHOW_ARROWS     => true,
			self::SHOW_PAGINATION => false,
			self::SLIDES          => [],
			self::THUMBNAILS      => [],
			self::CLASSES         => [],
			self::MAIN_CLASSES    => [],
			self::WRAPPER_CLASSES => [],
			self::SLIDE_CLASSES   => [],
			self::MAIN_ATTRS      => [],
			self::CAROUSEL_ATTRS  => [],
		];

		return wp_parse_args( $options, $defaults );
	}

	protected function main_classes(): array {
		$classes = [ 'c-slider__main', 'swiper-container' ];
		if ( $this->options[ self::SHOW_CAROUSEL ] ) {
			$classes[] = 'c-slider__main--has-carousel';
		}
		if ( $this->options[ self::SHOW_ARROWS ] ) {
			$classes[] = 'c-slider__main--has-arrows';
		}
		if ( $this->options[ self::SHOW_PAGINATION ] ) {
			$classes[] = 'c-slider__main--has-pagination';
		}

		return $classes;
	}

	public function get_data(): array {
		$data = [
			self::SLIDES          => $this->options[ self::SLIDES ],
			self::THUMBNAILS      => $this->options[ self::THUMBNAILS ],
			self::SHOW_CAROUSEL   => $this->options[ self::SHOW_CAROUSEL ],
			self::SHOW_ARROWS     => $this->options[ self::SHOW_ARROWS ],
			self::SHOW_PAGINATION => $this->options[ self::SHOW_PAGINATION ],
			self::CLASSES         => $this->merge_classes( [ 'c-slider' ], $this->options[ self::CLASSES ], true ),
			self::MAIN_CLASSES    => $this->merge_classes( $this->main_classes(), $this->options[ self::MAIN_CLASSES ], true ),
			self::SLIDE_CLASSES   => $this->merge_classes( [ 'c-slider__slide', 'swiper-slide' ], $this->options[ self::SLIDE_CLASSES ], true ),
			self::WRAPPER_CLASSES => $this->merge_classes( [ 'c-slider__wrapper', 'swiper-wrapper' ], $this->options[ self::WRAPPER_CLASSES ], true ),
			self::MAIN_ATTRS      => $this->merge_attrs( [ 'data-js' => 'c-slider' ], $this->options[ self::MAIN_ATTRS ], true ),
			self::CAROUSEL_ATTRS  => $this->merge_attrs( [ 'data-js' => 'c-slider-carousel' ], $this->options[ self::CAROUSEL_ATTRS ], true ),
		];


		return $data;
	}
}
