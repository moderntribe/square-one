<?php


namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\ImageText as ImageTextPanel;

class ImageText extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	protected function get_mapped_panel_data(): array {

		$data = [
			'image'             => $this->get_panel_image(),
			'wrapper_classes'   => $this->get_panel_classes(),
			'component_classes' => $this->get_panel_component_classes(),
			'content'           => ! empty( $this->panel_vars[ ImageTextPanel::FIELD_CONTENT ] ) ? $this->panel_vars[ ImageTextPanel::FIELD_CONTENT ] : false,
			'content_attr'      => $this->get_panel_attrs(),
			'title'             => $this->get_panel_title(),
			'cta'               => ! empty( $this->panel_vars[ ImageTextPanel::FIELD_CTA ] ) && ! empty( $this->panel_vars[ ImageTextPanel::FIELD_CTA ]['url'] ) ? $this->panel_vars[ ImageTextPanel::FIELD_CTA ] : false,
		];

		return $data;
	}

	protected function get_panel_image() {

		if ( empty( $this->panel_vars[ ImageTextPanel::FIELD_IMAGE ] ) ) {
			return false;
		}

		$options = [
			'as_bg'         => true,
			'use_lazyload'  => false,
			'echo'          => false,
			'wrapper_class' => 'c-image__bg',
		];

		return the_tribe_image( $this->panel_vars[ ImageTextPanel::FIELD_IMAGE ], $options );
	}

	protected function get_panel_classes() {

		$classes = [];

		if ( ImageTextPanel::FIELD_LAYOUT_OPTION_IMAGE_RIGHT === $this->panel_vars[ ImageTextPanel::FIELD_LAYOUT ] ) {
			$classes[] = 'site-grid--reorder-2-col';
		}

		return implode( ' ', $classes );
	}

	protected function get_panel_title() {

		return the_panel_title( esc_html( $this->panel_vars[ ImageTextPanel::FIELD_TITLE ] ), 'c-text__title', 'title', true, 0, esc_attr( get_nest_index() ) );
	}

	protected function get_panel_component_classes() {

		$classes = [ 'c-image' ];

		if ( ! empty( ImageTextPanel::NAME ) ) {
			$classes[] = 'c-image--rect';
		}

		return implode( ' ', $classes );
	}

	protected function get_panel_attrs() {
		$content_attrs = sprintf( 'data-name="%s" data-index="%s" data-autop="true" data-livetext',
			esc_attr( ImageTextPanel::FIELD_CONTENT ),
			esc_attr( get_nest_index() )
		);

		return $content_attrs;
	}

}
