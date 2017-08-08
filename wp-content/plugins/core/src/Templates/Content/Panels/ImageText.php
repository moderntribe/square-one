<?php


namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\ImageText as ImageTextPanel;
use Tribe\Project\Templates\Components\Card;
use Tribe\Project\Templates\Components\Image;

class ImageText extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	protected function get_mapped_panel_data(): array {

		$data = [
			'wrapper_classes' => $this->get_panel_classes(),
			'image'           => $this->get_panel_image(),
			'card'            => $this->get_card(),
		];

		return $data;
	}

	protected function get_card(): string {

		if ( empty( $this->panel_vars[ ImageTextPanel::FIELD_DESCRIPTION ] ) ) {
			return '';
		}

		$options = [
			Card::TITLE       => the_panel_title( esc_html( $this->panel_vars[ ImageTextPanel::FIELD_TITLE ] ), 'site-section__title', 'title', true, 0, 0 ),
			Card::DESCRIPTION => $this->panel_vars[ ImageTextPanel::FIELD_DESCRIPTION ],
			Card::IMAGE       => false,
			Card::CTA         => $this->panel_vars[ ImageTextPanel::FIELD_CTA ],
		];

		$card_obj = Card::factory( $options );

		return $card_obj->render();
	}

	protected function get_panel_image(): string {

		if ( empty( $this->panel_vars[ ImageTextPanel::FIELD_IMAGE ] ) ) {
			return '';
		}

		$options = [
			'component_class' => 'c-image c-image--rect',
			'as_bg'           => true,
			'use_lazyload'    => false,
			'echo'            => false,
			'wrapper_class'   => 'c-image__bg',
		];

		$image_obj = Image::factory( $this->panel_vars[ ImageTextPanel::FIELD_IMAGE ], $options );

		return $image_obj->render();
	}

	protected function get_panel_classes() {

		$classes = [];

		if ( ImageTextPanel::FIELD_LAYOUT_OPTION_IMAGE_RIGHT === $this->panel_vars[ ImageTextPanel::FIELD_LAYOUT ] ) {
			$classes[] = 'site-grid--reorder-2-col';
		}

		return implode( ' ', $classes );
	}

	protected function get_title_attrs() {
		if ( ! is_panel_preview() ) {
			return [];
		}

		return [
			'data-depth'    => 0,
			'data-name'     => ImageTextPanel::FIELD_TITLE,
			'data-index'    => esc_attr( get_nest_index() ),
			'data-livetext' => true,
		];
	}
}
