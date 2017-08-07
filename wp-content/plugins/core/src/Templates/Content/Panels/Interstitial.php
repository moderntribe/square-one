<?php


namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Interstitial as Interstice;

class Interstitial extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	protected function get_image() {

		if ( empty( $this->panel_vars[ Interstice::FIELD_IMAGE ] ) ) {
			return false;
		}

		$options = [
			'as_bg'         => true,
			'use_lazyload'  => false,
			'echo'          => false,
			'wrapper_class' => 'c-image__bg',
		];

		return the_tribe_image( $this->panel_vars[ Interstice::FIELD_IMAGE ], $options );
	}

	protected function get_layout() {

		$classes = [];

		if ( Interstice::FIELD_LAYOUT_OPTION_CONTENT_RIGHT === $this->panel_vars[ Interstice::FIELD_LAYOUT ] ) {
			$classes[] = 'site-grid--pull-right';
		}

		if ( Interstice::FIELD_LAYOUT_OPTION_CONTENT_CENTER === $this->panel_vars[ Interstice::FIELD_LAYOUT ] ) {
			$classes[] = 'site-grid--center';
		}

		return implode( ' ', $classes );
	}

	protected function get_title() {

		return the_panel_title( esc_html( $this->panel_vars[ Interstice::FIELD_TITLE ] ), 'c-text__title', 'title', true, 0, 0 );
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'image'       => $this->get_image(),
			'title'       => $this->get_title(),
			'description' => ! empty( $this->panel_vars[ Interstice::FIELD_DESCRIPTION ] ) ? $this->panel_vars[ Interstice::FIELD_DESCRIPTION ] : false,
			'btn_url'     => esc_url( $this->panel_vars[ Interstice::FIELD_CTA ]['url'] ),
			'btn_target'  => esc_attr( $this->panel_vars[ Interstice::FIELD_CTA ]['target'] ),
			'btn_label'   => esc_attr( $this->panel_vars[ Interstice::FIELD_CTA ]['label'] ),
			'layout'      => $this->get_layout(),
		];

		return $data;
	}
}
