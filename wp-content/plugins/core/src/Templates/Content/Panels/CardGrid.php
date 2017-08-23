<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\CardGrid as CardGridPanel;
use Tribe\Project\Templates\Components\Card;

class CardGrid extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'       => $this->get_title( CardGridPanel::FIELD_TITLE, [ 'site-section__title', 'h2' ] ),
			'description' => ! empty( $this->panel_vars[ CardGridPanel::FIELD_DESCRIPTION ] ) ? $this->panel_vars[ CardGridPanel::FIELD_DESCRIPTION ] : false,
			'cards'       => $this->get_the_cards(),
		];

		return $data;
	}

	public function get_the_cards(): array {
		$cards = [];

		if ( ! empty( $this->panel_vars[ CardGridPanel::FIELD_CARDS ] ) ) {
			for ( $i = 0; $i < count( $this->panel_vars[ CardGridPanel::FIELD_CARDS ] ); $i ++ ) {

				$card              = $this->panel_vars[ CardGridPanel::FIELD_CARDS ][ $i ];
				$title_attrs       = [];
				$description_attrs = [];

				if ( is_panel_preview() ) {
					$title_attrs = [
						'data-depth'    => $this->panel->get_depth(),
						'data-name'     => CardGridPanel::FIELD_CARD_TITLE,
						'data-index'    => $i,
						'data-livetext' => true,
					];

					$description_attrs = [
						'data-depth'    => $this->panel->get_depth(),
						'data-name'     => CardGridPanel::FIELD_CARD_DESCRIPTION,
						'data-index'    => $i,
						'data-autop'    => 'true',
						'data-livetext' => true,
					];
				}

				$options = [
					Card::TITLE       => $card[ CardGridPanel::FIELD_CARD_TITLE ],
					Card::TEXT        => $card[ CardGridPanel::FIELD_CARD_DESCRIPTION ],
					Card::IMAGE       => $card[ CardGridPanel::FIELD_CARD_IMAGE ],
					Card::CTA         => $card[ CardGridPanel::FIELD_CARD_CTA ],
					Card::TITLE_ATTRS => $title_attrs,
					Card::TEXT_ATTRS  => $description_attrs,
				];

				$card_obj = Card::factory( $options );
				$cards[]  = $card_obj->render();
			}
		}

		return $cards;
	}
}
