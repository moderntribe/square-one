<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Testimonial as TestimonialPanel;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Quote;
use Tribe\Project\Templates\Components\Slider;

class Testimonial extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'      => $this->get_title( $this->panel_vars[ TestimonialPanel::FIELD_TITLE ], [ 's-title', 'h5' ] ),
			'text_color' => $this->text_color(),
			'image'      => $this->get_image(),
			'slider'     => $this->get_slider(),
		];

		return $data;
	}

	protected function get_image() {

		if ( empty( $this->panel_vars[ TestimonialPanel::FIELD_IMAGE ] ) ) {
			return false;
		}

		$options = [
			Image::IMG_ID          => $this->panel_vars[ TestimonialPanel::FIELD_IMAGE ],
			Image::IMG_CLASSES     => 'c-image',
			Image::AS_BG           => true,
			Image::USE_LAZYLOAD    => false,
			Image::ECHO            => false,
			Image::WRAPPER_CLASSES => [ 'c-image__bg' ],
		];

		$image_obj = Image::factory( $options );

		return $image_obj->render();
	}

	protected function get_slider(): string {
		$main_attrs = [];
		if ( is_panel_preview() ) {
			$main_attrs[ 'data-depth' ]    = $this->panel->get_depth();
			$main_attrs[ 'data-name' ]     = 'quotes';
			$main_attrs[ 'data-livetext' ] = true;
		}
		$options = [
			Slider::SLIDES          => $this->get_slides(),
			Slider::SHOW_CAROUSEL   => false,
			Slider::SHOW_ARROWS     => false,
			Slider::SHOW_PAGINATION => true,
			Slider::MAIN_CLASSES    => $this->get_slider_main_classes(),
			Slider::MAIN_ATTRS      => $main_attrs,
		];

		$slider = Slider::factory( $options );

		return $slider->render();
	}

	protected function get_slides(): array {
		$quotes = [];

		if ( ! empty( $this->panel_vars[ TestimonialPanel::FIELD_QUOTES ] ) ) {
			for ( $i = 0; $i < count( $this->panel_vars[ TestimonialPanel::FIELD_QUOTES ] ); $i ++ ) {

				$quote       = $this->panel_vars[ TestimonialPanel::FIELD_QUOTES ][ $i ];
				$quote_attrs = [];
				$cite_attrs  = [];

				if ( is_panel_preview() ) {
					$quote_attrs = [
						'data-depth'    => $this->panel->get_depth(),
						'data-name'     => TestimonialPanel::FIELD_QUOTE,
						'data-index'    => $i,
						'data-autop'    => 'true',
						'data-livetext' => true,
					];

					$cite_attrs = [
						'data-depth'    => $this->panel->get_depth(),
						'data-name'     => TestimonialPanel::FIELD_CITE,
						'data-index'    => $i,
						'data-livetext' => true,
					];
				}

				$options = [
					Quote::QUOTE       => $quote[ TestimonialPanel::FIELD_QUOTE ],
					Quote::CITE        => $quote[ TestimonialPanel::FIELD_CITE ],
					Quote::QUOTE_ATTRS => $quote_attrs,
					Quote::CITE_ATTRS  => $cite_attrs,
				];

				$quote_obj = Quote::factory( $options );
				$quotes[]  = $quote_obj->render();
			}
		}

		return $quotes;
	}

	protected function text_color() {

		$classes = [];

		if ( TestimonialPanel::FIELD_TEXT_LIGHT === $this->panel_vars[ TestimonialPanel::FIELD_TEXT_COLOR ] ) {
			$classes[] = 't-content--light';
		}

		if ( TestimonialPanel::FIELD_TEXT_DARK === $this->panel_vars[ TestimonialPanel::FIELD_TEXT_COLOR ] ) {
			$classes[] = 't-content--dark';
		}

		return implode( ' ', $classes );
	}

	protected function get_slider_main_classes() {
		$classes = [ 'c-slider__main' ];

		return $classes;
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/testimonial'];
	}
}
