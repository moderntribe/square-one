<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\CardGrid as CardG;

class CardGrid extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_title(): string {
		$title = '';

		if ( ! empty( $this->panel_vars[ CardG::FIELD_TITLE ] ) ) {
			$title = the_panel_title( esc_html( $this->panel_vars[ CardG::FIELD_TITLE ] ), 'section__title', 'title', true, 0, 0 );
		}

		return $title;
	}

	public function get_the_cards(): array {
		$cards = [];

		if ( ! empty( $this->panel_vars[ CardG::FIELD_CARDS ] ) ) {
			foreach ( $this->panel_vars[ CardG::FIELD_CARDS ] as $card ) {

				$cards[] = [
					'title'           => the_panel_title( esc_html( $card[ CardG::FIELD_CARD_TITLE ] ), 'c-card__title', 'title', true, 0, esc_attr( get_nest_index() ) ),
					'description'     => $card[ CardG::FIELD_CARD_DESCRIPTION ],
					'card_desc_attrs' => $this->get_card_content_attrs(),
					'image'           => $this->get_card_image( $card[ CardG::FIELD_CARD_IMAGE ] ),
					'url'             => esc_url( $card[ CardG::FIELD_CARD_CTA ]['url'] ),
					'label'           => esc_html( $card[ CardG::FIELD_CARD_CTA ]['label'] ),
					'target'          => esc_attr( $card[ CardG::FIELD_CARD_CTA ]['target'] ),
				];
			}
		}

		return $cards;
	}

	protected function get_card_image( $img ) {

		if ( empty( $img ) ) {
			return false;
		}

		$options = [
			'as_bg'         => false,
			'use_lazyload'  => false,
			'echo'          => false,
			'wrapper_class' => 'c-image',
			'src_size'      => 'component-card',
		];

		return the_tribe_image( $img, $options );
	}

	protected function get_card_content_attrs() {
		$card_desc_attrs = sprintf( 'data-depth="0" data-name="%s" data-index="%s" data-autop="true" data-livetext',
			esc_attr( CardG::FIELD_CARD_DESCRIPTION ),
			esc_attr( get_nest_index() )
		);

		return $card_desc_attrs;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'       => $this->get_title(),
			'description' => ! empty( $this->panel_vars[ CardG::FIELD_DESCRIPTION ] ) ? $this->panel_vars[ CardG::FIELD_DESCRIPTION ] : false,
			'cards'       => $this->get_the_cards(),
		];

		return $data;
	}
}
