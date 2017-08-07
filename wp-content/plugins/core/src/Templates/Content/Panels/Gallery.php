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

	public function get_title(): string {
		$title = '';

		if ( ! empty( $this->panel_vars[ GalleryPanel::FIELD_TITLE ] ) ) {
			$title = the_panel_title( esc_html( $this->panel_vars[ GalleryPanel::FIELD_TITLE ] ), 'section__title', 'title', true, 0, 0 );
		}

		return $title;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'  => $this->get_title(),
			'slider' => $this->get_slider(),
		];

		return $data;
	}

	protected function get_slider(): string {
		$options = [
			'slides'        => $this->get_slides(),
			'thumbnails'    => $this->get_slides( 'thumbnail' ),
			'show_carousel' => true,
			'show_arrows'   => true,
		];

		$slider = SliderComponent::factory( $options );
		return $slider->render();
	}

	protected function get_slides( $size = 'full' ): array {
		$slide_ids = $this->panel_vars[GalleryPanel::FIELD_GALLERY];

		if ( empty( $slide_ids ) ) {
			return [];
		}

		return array_map( function ( $slide_id ) use ( $size ) {
			$options = [
				'as_bg'        => false,
				'use_lazyload' => false,
				'echo'         => false,
				'src_size'     => $size,
			];

			$image = ImageComponent::factory( $slide_id, $options );
			return $image->render();
		}, $slide_ids );
	}
}
