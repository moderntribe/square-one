<?php

namespace Tribe\Project\Templates\Controllers\Panel;

use Exception;
use Tribe\Project\Panels\Types\ContentSlider as ContentSliderPanel;
use Tribe\Project\Templates\Components\Content_Block;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Link;
use Tribe\Project\Templates\Components\Panels\Content_Slider as Content_Slider_Context;
use Tribe\Project\Templates\Components\Slider as SliderComponent;
use Tribe\Project\Templates\Components\Text;

class Content_Slider extends Panel {
	use Traits\Unwrapped;
	use Traits\Headerless;

	protected function render_content( \ModularContent\Panel $panel, array $panel_vars ): string {
		return $this->factory->get( Content_Slider_Context::class, [
			Content_Slider_Context::SLIDER => $this->get_slider( $panel, $panel_vars ),
		] )->render();
	}

	protected function get_slider( \ModularContent\Panel $panel, array $panel_vars ): string {
		$main_attrs = [];
		if ( is_panel_preview() ) {
			$main_attrs['data-depth']    = $panel->get_depth();
			$main_attrs['data-name']     = SliderComponent::SLIDES;
			$main_attrs['data-livetext'] = true;
		}
		$options = [
			SliderComponent::SLIDES          => $this->get_slides( $panel, $panel_vars ),
			SliderComponent::THUMBNAILS      => false,
			SliderComponent::SHOW_CAROUSEL   => false,
			SliderComponent::SHOW_ARROWS     => true,
			SliderComponent::SHOW_PAGINATION => false,
			SliderComponent::MAIN_CLASSES    => $this->get_slider_main_classes(),
			SliderComponent::MAIN_ATTRS      => $main_attrs,
		];

		return $this->factory->get( SliderComponent::class, $options )->render();
	}

	protected function get_slides( \ModularContent\Panel $panel, array $panel_vars ): array {
		$depth  = $panel->get_depth();
		$slides = [];

		if ( ! empty( $panel_vars[ ContentSliderPanel::FIELD_SLIDES ] ) ) {
			$index = 0;
			foreach ( $panel_vars[ ContentSliderPanel::FIELD_SLIDES ] as $slide ) {

				$slide_markup = '';

				try {
					$options = [
						Image::ATTACHMENT      => \Tribe\Project\Templates\Models\Image::factory( $slide[ ContentSliderPanel::FIELD_SLIDE_IMAGE ] ),
						Image::AS_BG           => true,
						Image::USE_LAZYLOAD    => false,
						Image::WRAPPER_CLASSES => [ 'c-image__bg' ],
					];

					$slide_markup .= $this->factory->get( Image::class, $options )->render();
				} catch ( Exception $e ) {
					// no valid image for this slide
				}

				$options = [
					Content_Block::TITLE           => $this->get_content_block_title( $slide, $index, $depth ),
					Content_Block::TEXT            => $this->get_content_block_text( $slide, $index, $depth ),
					Content_Block::CLASSES         => [ 't-content c-content-block--content-slider' ],
					Content_Block::CONTENT_CLASSES => [ 'c-content-block__desc--content-slider' ],
					Content_Block::BUTTON          => $this->get_content_block_button( $slide ),
				];

				$slide_markup .= $this->factory->get( Content_Block::class, $options )->render();

				$slides[] = $slide_markup;
				$index ++;
			}
		}

		return $slides;
	}

	protected function get_slider_main_classes(): array {
		return [ 'c-slider__main' ];
	}

	protected function get_content_block_title( $slide, $index, $depth ): string {
		if ( ! is_panel_preview() && empty( $slide[ ContentSliderPanel::FIELD_SLIDE_TITLE ] ) ) {
			return '';
		}
		$attrs = [];
		if ( is_panel_preview() ) {
			$attrs = [
				'data-depth'    => $depth,
				'data-name'     => ContentSliderPanel::FIELD_SLIDE_TITLE,
				'data-index'    => $index,
				'data-livetext' => true,
			];
		}
		$options = [
			Text::CLASSES => [ 'h2' ],
			Text::TEXT    => esc_html( $slide[ ContentSliderPanel::FIELD_SLIDE_TITLE ] ),
			Text::ATTRS   => $attrs,
			Text::TAG     => 'h2',
		];

		return $this->factory->get( Text::class, $options )->render();
	}

	protected function get_content_block_text( $slide, $index, $depth ): string {

		if ( empty( $slide[ ContentSliderPanel::FIELD_SLIDE_CONTENT ] ) && ! is_panel_preview() ) {
			return '';
		}
		$attrs = [];
		if ( is_panel_preview() ) {
			$attrs = [
				'data-depth'    => $depth,
				'data-name'     => ContentSliderPanel::FIELD_SLIDE_CONTENT,
				'data-index'    => $index,
				'data-autop'    => 'true',
				'data-livetext' => true,
			];
		}

		$options = [
			Text::ATTRS   => $attrs,
			Text::CLASSES => [],
			Text::TEXT    => $slide[ ContentSliderPanel::FIELD_SLIDE_CONTENT ],
		];

		return $this->factory->get( Text::class, $options )->render();
	}

	protected function get_content_block_button( $slide ): string {
		if ( empty( $slide[ ContentSliderPanel::FIELD_SLIDE_CTA ][ Link::BODY ] ) || empty( $slide[ ContentSliderPanel::FIELD_SLIDE_CTA ][ Link::URL ] ) ) {
			return '';
		}
		$options = [
			Link::CLASSES => [ 'c-btn', 'c-btn--sm' ],
			Link::TARGET  => $slide[ ContentSliderPanel::FIELD_SLIDE_CTA ][ Link::TARGET ],
			Link::BODY    => $slide[ ContentSliderPanel::FIELD_SLIDE_CTA ][ Link::BODY ],
			Link::URL     => $slide[ ContentSliderPanel::FIELD_SLIDE_CTA ][ Link::URL ],
		];

		return $this->factory->get( Link::class, $options )->render();
	}
}
