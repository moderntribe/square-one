<?php

namespace Tribe\Project\Templates\Controllers\Panels;

use Tribe\Project\Panels\Types\Gallery as GalleryPanel;
use Tribe\Project\Templates\Components\Image as ImageComponent;
use Tribe\Project\Templates\Components\Panels\Gallery as Gallery_Context;
use Tribe\Project\Templates\Components\Slider as SliderComponent;

class Gallery extends Panel {
	protected function render_content( \ModularContent\Panel $panel, array $panel_vars ): string {
		return $this->factory->get( Gallery_Context::class, [
			Gallery_Context::SLIDER => $this->get_slider( $panel_vars ),
		] )->render();
	}

	protected function get_slider( array $panel_vars ): string {
		$options = [
			SliderComponent::SLIDES          => $this->get_slides( $panel_vars ),
			SliderComponent::THUMBNAILS      => $this->get_slides( $panel_vars, 'thumbnail' ),
			SliderComponent::SHOW_CAROUSEL   => $this->show_carousel( $panel_vars ),
			SliderComponent::SHOW_ARROWS     => true,
			SliderComponent::SHOW_PAGINATION => true,
			SliderComponent::MAIN_CLASSES    => $this->get_slider_main_classes( $panel_vars ),
			SliderComponent::MAIN_ATTRS      => [ 'data-swiper-options' => '{"speed":600}' ],
			SliderComponent::CAROUSEL_ATTRS  => [ 'data-swiper-options' => '{"speed":600}' ],
		];

		return $this->factory->get( SliderComponent::class, $options )->render();
	}

	protected function show_carousel( array $panel_vars ): bool {
		$show_carousel = true;

		if ( ! empty( $panel_vars[ GalleryPanel::FIELD_CAROUSEL ] ) && $panel_vars[ GalleryPanel::FIELD_CAROUSEL ] === GalleryPanel::FIELD_CAROUSEL_HIDE ) {
			$show_carousel = false;
		}

		return $show_carousel;
	}

	protected function use_crop( array $panel_vars ): bool {
		$use_crop = true;

		if ( ! empty( $panel_vars[ GalleryPanel::FIELD_IMAGE_TREATMENT ] ) && $panel_vars[ GalleryPanel::FIELD_IMAGE_TREATMENT ] === GalleryPanel::FIELD_IMAGE_TREATMENT_OPTION_LETTERBOX ) {
			$use_crop = false;
		}

		return $use_crop;
	}

	protected function get_slides( array $panel_vars, $size = 'full' ): array {
		$slide_ids = $panel_vars[ GalleryPanel::FIELD_GALLERY ];

		if ( empty( $slide_ids ) ) {
			return [];
		}

		$crop = $this->use_crop( $panel_vars );

		return array_filter( array_map( function ( $slide_id ) use ( $size, $crop ) {
			try {
				$image = \Tribe\Project\Templates\Models\Image::factory( $slide_id );
			} catch ( \Exception $e ) {
				return '';
			}

			$options = [
				ImageComponent::ATTACHMENT   => $image,
				ImageComponent::AS_BG        => $crop && $size === 'full',
				ImageComponent::USE_LAZYLOAD => false,
				ImageComponent::SRC_SIZE     => $size,
			];

			return $this->factory->get( ImageComponent::class, $options )->render();
		}, $slide_ids ) );
	}

	protected function get_slider_main_classes( array $panel_vars ): array {
		return [ sprintf( 'c-slider__main--%s', $panel_vars[ GalleryPanel::FIELD_IMAGE_TREATMENT ] ) ];
	}
}
