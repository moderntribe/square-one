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

	public function get_title(): string {
		$title = '';

		if ( ! empty( $this->panel_vars[ TestimonialPanel::FIELD_TITLE ] ) ) {
			$title = the_panel_title( esc_html( $this->panel_vars[ TestimonialPanel::FIELD_TITLE ] ), 'site-section__title h5', 'title', true, 0, 0 );
		}

		return $title;
	}

	protected function get_image() {

		if ( empty( $this->panel_vars[ TestimonialPanel::FIELD_IMAGE ] ) ) {
			return false;
		}

		$options = [
			'img_id'          => $this->panel_vars[ TestimonialPanel::FIELD_IMAGE ],
			'component_class' => 'c-image',
			'as_bg'           => true,
			'use_lazyload'    => false,
			'echo'            => false,
			'wrapper_class'   => 'c-image__bg',
		];

		$image_obj = Image::factory( $options );

		return $image_obj->render();
	}

	protected function get_slider(): string {
		$options = [
			Slider::SLIDES          => $this->get_slides(),
			Slider::SHOW_CAROUSEL   => false,
			Slider::SHOW_ARROWS     => false,
			Slider::SHOW_PAGINATION => true,
			Slider::MAIN_CLASSES    => $this->get_slider_main_classes(),
		];

		$slider = Slider::factory( $options );

		return $slider->render();
	}

	public function get_slides(): array {
		$quotes = [];

		if ( ! empty( $this->panel_vars[ TestimonialPanel::FIELD_QUOTES ] ) ) {
			for ( $i = 0; $i < count( $this->panel_vars[ TestimonialPanel::FIELD_QUOTES ] ); $i++ ) {

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

		if ( TestimonialPanel::FIELD_TEXT_WHITE === $this->panel_vars[ TestimonialPanel::FIELD_TEXT_COLOR ] ) {
			$classes[] = 't-content--light';
		}

		if ( TestimonialPanel::FIELD_TEXT_BLACK === $this->panel_vars[ TestimonialPanel::FIELD_TEXT_COLOR ] ) {
			$classes[] = 't-content--dark';
		}

		return implode( ' ', $classes );
	}

	protected function get_slider_main_classes() {
		$classes = [ 'c-slider__main' ];
		return $classes;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'      => $this->get_title(),
			'text_color' => $this->text_color(),
			'image'      => $this->get_image(),
			'slider'     => $this->get_slider(),
		];

		return $data;
	}
}