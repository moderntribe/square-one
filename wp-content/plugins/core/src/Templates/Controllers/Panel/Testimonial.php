<?php

namespace Tribe\Project\Templates\Controllers\Panel;

use Exception;
use Tribe\Project\Panels\Types\Testimonial as TestimonialPanel;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Panels\Panel as Panel_Context;
use Tribe\Project\Templates\Components\Panels\Testimonial as Testimonial_Context;
use Tribe\Project\Templates\Components\Quote;
use Tribe\Project\Templates\Components\Slider;

class Testimonial extends Panel {
	protected function get_panel_context_options( \ModularContent\Panel $panel, $panel_vars ): array {
		$options                                   = parent::get_panel_context_options( $panel, $panel_vars );
		$options[ Panel_Context::WRAPPER_CLASSES ] = $this->text_color( $panel_vars );
		$options[ Panel_Context::HEADER_CLASSES ]  = [ 's-header--no-padding' ];

		return $options;
	}

	protected function get_title_classes( array $panel_vars ): array {
		return [ 's-title', 'h5' ];
	}

	protected function get_description( array $panel_vars ): string {
		return '';
	}

	protected function render_content( \ModularContent\Panel $panel, array $panel_vars ): string {
		return $this->factory->get( Testimonial_Context::class, [
			Testimonial_Context::IMAGE  => $this->get_image( $panel_vars ),
			Testimonial_Context::SLIDER => $this->get_slider( $panel, $panel_vars ),
		] )->render();
	}

	protected function get_image( array $panel_vars ): string {

		if ( empty( $panel_vars[ TestimonialPanel::FIELD_IMAGE ] ) ) {
			return '';
		}

		try {
			$image = \Tribe\Project\Templates\Models\Image::factory( $panel_vars[ TestimonialPanel::FIELD_IMAGE ] );
		} catch ( Exception $e ) {
			return '';
		}

		$options = [
			Image::ATTACHMENT      => $image,
			Image::IMG_CLASSES     => [ 'c-image' ],
			Image::AS_BG           => true,
			Image::USE_LAZYLOAD    => false,
			Image::WRAPPER_CLASSES => [ 'c-image__bg' ],
		];

		return $this->factory->get( Image::class, $options )->render();
	}

	protected function get_slider( \ModularContent\Panel $panel, array $panel_vars ): string {
		$depth      = $panel->get_depth();
		$main_attrs = [];
		if ( is_panel_preview() ) {
			$main_attrs['data-depth']    = $depth;
			$main_attrs['data-name']     = 'quotes';
			$main_attrs['data-livetext'] = true;
		}
		$options = [
			Slider::SLIDES          => $this->get_slides( $panel_vars, $depth ),
			Slider::SHOW_CAROUSEL   => false,
			Slider::SHOW_ARROWS     => false,
			Slider::SHOW_PAGINATION => true,
			Slider::MAIN_CLASSES    => [ 'c-slider__main' ],
			Slider::MAIN_ATTRS      => $main_attrs,
		];

		return $this->factory->get( Slider::class, $options )->render();
	}

	protected function get_slides( array $panel_vars, $depth ): array {
		$quotes = [];

		foreach ( $panel_vars[ TestimonialPanel::FIELD_QUOTES ] as $index => $quote ) {

			$quote_attrs = [];
			$cite_attrs  = [];

			if ( is_panel_preview() ) {
				$quote_attrs = [
					'data-depth'    => $depth,
					'data-name'     => TestimonialPanel::FIELD_QUOTE,
					'data-index'    => $index,
					'data-autop'    => 'true',
					'data-livetext' => true,
				];

				$cite_attrs = [
					'data-depth'    => $depth,
					'data-name'     => TestimonialPanel::FIELD_CITE,
					'data-index'    => $index,
					'data-livetext' => true,
				];
			}

			$options = [
				Quote::QUOTE       => $quote[ TestimonialPanel::FIELD_QUOTE ],
				Quote::CITE        => $quote[ TestimonialPanel::FIELD_CITE ],
				Quote::QUOTE_ATTRS => $quote_attrs,
				Quote::CITE_ATTRS  => $cite_attrs,
			];

			$quotes[] = $this->factory->get( Quote::class, $options )->render();
		}

		return $quotes;
	}

	protected function text_color( array $panel_vars ): array {

		$classes = [];

		if ( TestimonialPanel::FIELD_TEXT_LIGHT === $panel_vars[ TestimonialPanel::FIELD_TEXT_COLOR ] ) {
			$classes[] = 't-theme--light';
		}

		if ( TestimonialPanel::FIELD_TEXT_DARK === $panel_vars[ TestimonialPanel::FIELD_TEXT_COLOR ] ) {
			$classes[] = 't-theme--dark';
		}

		return $classes;
	}
}
