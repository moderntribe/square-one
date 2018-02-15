<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Gallery as GalleryPanel;
use Tribe\Project\Templates\Components\Image as ImageComponent;
use Tribe\Project\Templates\Components\Slider as SliderComponent;

class Gallery extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'  => $this->get_title( $this->panel_vars[ GalleryPanel::FIELD_TITLE ], [ 'section__title' ] ),
			'slider' => $this->get_slider(),
		];

		return $data;
	}

	protected function get_slider(): string {
		$options = [
			SliderComponent::SLIDES          => $this->get_slides(),
			SliderComponent::THUMBNAILS      => $this->get_slides( 'thumbnail' ),
			SliderComponent::SHOW_CAROUSEL   => $this->show_carousel(),
			SliderComponent::SHOW_ARROWS     => true,
			SliderComponent::SHOW_PAGINATION => true,
			SliderComponent::MAIN_CLASSES    => $this->get_slider_main_classes(),
			SliderComponent::MAIN_ATTRS      => [ 'data-swiper-options' => '{"speed":600}' ],
			SliderComponent::CAROUSEL_ATTRS  => [ 'data-swiper-options' => '{"speed":600}' ],
		];

		$slider = SliderComponent::factory( $options );

		return $slider->render();
	}

	protected function show_carousel(): bool {
		$show_carousel = true;

		if ( ! empty( $this->panel_vars[ GalleryPanel::FIELD_CAROUSEL ] ) && $this->panel_vars[ GalleryPanel::FIELD_CAROUSEL ] == GalleryPanel::FIELD_CAROUSEL_HIDE ) {
			$show_carousel = false;
		}

		return $show_carousel;
	}

	protected function use_crop(): bool {
		$use_crop = true;

		if ( ! empty( $this->panel_vars[ GalleryPanel::FIELD_IMAGE_TREATMENT ] ) && $this->panel_vars[ GalleryPanel::FIELD_IMAGE_TREATMENT ] == GalleryPanel::FIELD_IMAGE_TREATMENT_OPTION_LETTERBOX ) {
			$use_crop = false;
		}

		return $use_crop;
	}

	protected function get_slides( $size = 'full' ): array {
		$slide_ids = $this->panel_vars[ GalleryPanel::FIELD_GALLERY ];

		if ( empty( $slide_ids ) ) {
			return [];
		}

		return array_map( function ( $slide_id ) use ( $size ) {
			$options = [
				'img_id'       => $slide_id,
				'as_bg'        => $this->use_crop() && $size == 'full',
				'use_lazyload' => false,
				'echo'         => false,
				'src_size'     => $size,
			];

			$image = ImageComponent::factory( $options );

			return $image->render();
		}, $slide_ids );
	}

	protected function get_slider_main_classes() {
		$classes = [ sprintf( 'c-slider__main--%s', $this->panel_vars[ GalleryPanel::FIELD_IMAGE_TREATMENT ] ) ];

		return $classes;
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/gallery'];
	}
}
