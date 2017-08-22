<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\ImgSlider as ImgSliderPanel;
use Tribe\Project\Templates\Components\Slider as SliderComponent;
use Tribe\Project\Templates\Components\Image;

class ImgSlider extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_title(): string {
		$title = '';

		if ( ! empty( $this->panel_vars[ ImgSliderPanel::FIELD_TITLE ] ) ) {
			$title = the_panel_title( esc_html( $this->panel_vars[ ImgSliderPanel::FIELD_TITLE ] ), 'section__title', 'title', true, 0, 0 );
		}

		return $title;
	}

	protected function get_slider(): string {
		$options = [
			SliderComponent::SLIDES          => $this->get_slides(),
			SliderComponent::THUMBNAILS      => false,
			SliderComponent::SHOW_CAROUSEL   => false,
			SliderComponent::SHOW_ARROWS     => true,
			SliderComponent::SHOW_PAGINATION => false,
			SliderComponent::MAIN_CLASSES    => $this->get_slider_main_classes(),
		];

		$slider = SliderComponent::factory( $options );

		return $slider->render();
	}

	protected function get_slides(): array {
		$slides = [];

		if ( ! empty( $this->panel_vars[ ImgSliderPanel::FIELD_SLIDES ] ) ) {
			for ( $i = 0; $i < count( $this->panel_vars[ ImgSliderPanel::FIELD_SLIDES ] ); $i++ ) {

				$slide = $this->panel_vars[ ImgSliderPanel::FIELD_SLIDES ][ $i ];

				$options = [
					Image::IMG_ID        => $slide[ ImgSliderPanel::FIELD_SLIDE_IMAGE ],
					Image::AS_BG         => true,
					Image::USE_LAZYLOAD  => false,
					Image::WRAPPER_CLASS => 'c-image__bg',
				];

				$slide_obj = Image::factory( $options );
				$slides[]  = $slide_obj->render();
			}
		}

		return $slides;
	}

	protected function get_slider_main_classes() {
		$classes = [ 'c-slider__main' ];
		return $classes;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'slider' => $this->get_slider(),
		];

		return $data;
	}
}
