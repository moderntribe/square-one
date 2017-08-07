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

	protected function get_mapped_panel_data(): array {

		$data = [
			'wrapper_classes'      => $this->get_panel_classes(),
			'image'                => $this->get_panel_image(),
			'image_classes'        => $this->get_image_classes(),
			'card_content_classes' => $this->get_card_content_classes(),
			'description'          => $this->panel_vars[ Interstice::FIELD_DESCRIPTION ],
			'desc_classes'         => $this->get_description_classes(),
			'desc_attrs'           => $this->get_card_desc_attrs(),
			'title'                => $this->get_panel_title(),
			'url'                  => esc_url( $this->panel_vars[ Interstice::FIELD_CTA ]['url'] ),
			'label'                => esc_html( $this->panel_vars[ Interstice::FIELD_CTA ]['label'] ),
			'target'               => esc_attr( $this->panel_vars[ Interstice::FIELD_CTA ]['target'] ),
			'cta_classes'          => $this->get_cta_classes(),
		];

		return $data;
	}

	protected function get_panel_image() {

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

	protected function get_panel_classes() {

		$classes = [];

		if ( Interstice::FIELD_LAYOUT_OPTION_CONTENT_RIGHT === $this->panel_vars[ Interstice::FIELD_LAYOUT ] ) {
			$classes[] = 'site-grid--pull-right';
		}

		if ( Interstice::FIELD_LAYOUT_OPTION_CONTENT_CENTER === $this->panel_vars[ Interstice::FIELD_LAYOUT ] ) {
			$classes[] = 'site-grid--center';
		}

		return implode( ' ', $classes );
	}

	protected function get_panel_title() {

		return the_panel_title( esc_html( $this->panel_vars[ Interstice::FIELD_TITLE ] ), 'c-text__title', 'title', true, 0, 0 );
	}

	protected function get_image_classes() {

		$classes = [ 'c-image' ];

		if ( ! empty( Interstice::NAME ) ) {
			$classes[] = '';
		}

		return implode( ' ', $classes );
	}

	protected function get_card_content_classes() {

		$classes = [ 'c-card__content' ];

		if ( ! empty( Interstice::NAME ) ) {
			$classes[] = '';
		}

		return implode( ' ', $classes );
	}

	protected function get_title_classes() {

		$classes = [ 'c-title' ];

		if ( ! empty( Interstice::NAME ) ) {
			$classes[] = '';
		}

		return implode( ' ', $classes );
	}

	protected function get_description_classes() {

		$classes = [ 'c-description' ];

		if ( ! empty( Interstice::NAME ) ) {
			$classes[] = '';
		}

		return implode( ' ', $classes );
	}

	protected function get_card_desc_attrs() {
		$card_desc_attrs = sprintf( 'data-depth="0" data-name="%s" data-autop="true" data-livetext',
			esc_attr( Interstice::FIELD_DESCRIPTION )
		);

		return $card_desc_attrs;
	}

	protected function get_cta_classes() {

		$classes = [ 'c-cta' ];

		if ( ! empty( Interstice::NAME ) ) {
			$classes[] = '';
		}

		return implode( ' ', $classes );
	}
}
