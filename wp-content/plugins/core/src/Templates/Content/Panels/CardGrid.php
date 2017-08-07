<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\CardGrid as CardG;
use Tribe\Project\Templates\Components\Card;

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
				$options = [
					Card::TITLE       => $card[ CardG::FIELD_CARD_TITLE ],
					Card::DESCRIPTION => $card[ CardG::FIELD_CARD_DESCRIPTION ],
					Card::IMAGE       => $card[ CardG::FIELD_CARD_IMAGE ],
					Card::CTA         => $card[ CardG::FIELD_CARD_CTA ],
				];

				$card_obj = Card::factory( $options );
				$cards[]  = $card_obj->render();
			}
		}

		return $cards;
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
