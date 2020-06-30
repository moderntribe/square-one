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

	public function render(): void {
		?>
		<div {{ container_classes }}>
			<div {{ main_classes }} {{ main_attrs }}>
				<div {{ wrapper_classes }}>
					{% for slide in slides %}
						<div {{ slide_classes }}>
							{{ slide }}
						</div>
					{% endfor %}
				</div>
				{% if show_arrows %}
					<div class="c-slider__arrows">
						<div class="c-slider__button c-slider__button--prev swiper-button-prev"></div>
						<div class="c-slider__button c-slider__button--next swiper-button-next"></div>
					</div>
				{% endif %}
				{% if show_pagination %}
					<div class="c-slider__pagination swiper-pagination" data-js="c-slider-pagination"></div>
				{% endif %}
			</div>
			{% if show_carousel %}
				<div class="c-slider__carousel swiper-container" {{ carousel_attrs }}>
					<div class="swiper-wrapper">
						{% for thumbnail in thumbnails %}
							<button class="c-slider__thumbnail swiper-slide" data-js="c-slider-thumb-trigger" data-index="{{ loop.index0 }}" aria-label="{{ __( 'Slide navigation for image' )|esc_html }} {{ loop.index }}">
								{{ thumbnail }}
							</button>
						{% endfor %}
					</div>
				</div>
			{% endif %}
		</div>
		<?php
	}
}
