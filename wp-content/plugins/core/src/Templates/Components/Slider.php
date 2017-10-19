<?php

namespace Tribe\Project\Templates\Components;

class Slider extends Component {

	const TEMPLATE_NAME = 'components/slider.twig';

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
			static::SHOW_CAROUSEL   => true,
			static::SHOW_ARROWS     => true,
			static::SHOW_PAGINATION => false,
			static::SLIDES          => [],
			static::THUMBNAILS      => [],
			static::CLASSES         => [],
			static::MAIN_CLASSES    => [],
			static::WRAPPER_CLASSES => [],
			static::SLIDE_CLASSES   => [],
			static::MAIN_ATTRS      => [],
			static::CAROUSEL_ATTRS  => [],
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
			static::SLIDES          => $this->options[ self::SLIDES ],
			static::THUMBNAILS      => $this->options[ self::THUMBNAILS ],
			static::SHOW_CAROUSEL   => $this->options[ self::SHOW_CAROUSEL ],
			static::SHOW_ARROWS     => $this->options[ self::SHOW_ARROWS ],
			static::SHOW_PAGINATION => $this->options[ self::SHOW_PAGINATION ],
			static::CLASSES         => $this->merge_classes( [ 'c-slider' ], $this->options[ static::CLASSES ], true ),
			static::MAIN_CLASSES    => $this->merge_classes( $this->main_classes(), $this->options[ static::MAIN_CLASSES ], true ),
			static::SLIDE_CLASSES   => $this->merge_classes( [ 'c-slider__slide', 'swiper-slide' ], $this->options[ static::SLIDE_CLASSES ], true ),
			static::WRAPPER_CLASSES => $this->merge_classes( [ 'c-slider__wrapper', 'swiper-wrapper' ], $this->options[ static::WRAPPER_CLASSES ], true ),
			static::MAIN_ATTRS      => $this->merge_attrs( [ 'data-js' => 'c-slider' ], $this->options[ static::MAIN_ATTRS ], true ),
			static::CAROUSEL_ATTRS  => $this->merge_attrs( [ 'data-js' => 'c-slider-carousel' ], $this->options[ static::CAROUSEL_ATTRS ], true ),
		];


		return $data;
	}
}