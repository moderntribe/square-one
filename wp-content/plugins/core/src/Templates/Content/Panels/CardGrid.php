<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\CardGrid as CardGridPanel;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Card;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Templates\Components\Title;
use Tribe\Project\Theme\Image_Sizes;

class CardGrid extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'             => $this->get_title( CardGridPanel::FIELD_TITLE, [ 'site-section__title', 'h2' ] ),
			'description'       => ! empty( $this->panel_vars[ CardGridPanel::FIELD_DESCRIPTION ] ) ? $this->panel_vars[ CardGridPanel::FIELD_DESCRIPTION ] : false,
			'cards'             => $this->get_cards(),
		];

		return $data;
	}

	protected function get_cards(): array {
		$cards = [];

		if ( ! empty( $this->panel_vars[ CardGridPanel::FIELD_CARDS ] ) ) {

			$i = 0;

			foreach ( $this->panel_vars[ CardGridPanel::FIELD_CARDS ] as $card ) {
				$options = [
					Card::TITLE  => $this->get_card_title( $card[ CardGridPanel::FIELD_CARD_TITLE ], $i ),
					Card::TEXT   => $this->get_card_text( $card[ CardGridPanel::FIELD_CARD_DESCRIPTION ], $i ),
					Card::IMAGE  => $this->get_card_image( $card[ CardGridPanel::FIELD_CARD_IMAGE ] ),
					Card::BUTTON => $this->get_card_button( $card[ CardGridPanel::FIELD_CARD_CTA ] ),
				];

				$card_obj = Card::factory( $options );
				$cards[]  = $card_obj->render();

				$i ++;
			}
		}

		return $cards;
	}

	protected function get_card_image( $image_id ) {
		if ( empty( $image_id ) ) {
			return false;
		}

		$options = [
			Image::IMG_ID       => $image_id,
			Image::AS_BG        => false,
			Image::USE_LAZYLOAD => false,
			Image::ECHO         => false,
			Image::SRC_SIZE     => Image_Sizes::COMPONENT_CARD,
		];

		$image_obj = Image::factory( $options );

		return $image_obj->render();
	}

	protected function get_card_title( $title, $index ) {
		$attrs = [];

		if ( is_panel_preview() ) {
			$attrs = [
				'data-depth'    => $this->get_depth(),
				'data-name'     => CardGridPanel::FIELD_CARD_TITLE,
				'data-index'    => $index,
				'data-livetext' => true,
			];
		}

		$options = [
			Title::TITLE   => $title,
			Title::CLASSES => [ 'c-card__title' ],
			Title::ATTRS   => $attrs,
			Title::TAG     => 'h3',
		];

		$title_obj = Title::factory( $options );

		return $title_obj->render();
	}

	protected function get_card_text( $text, $index ) {
		$attrs = [];

		if ( is_panel_preview() ) {
			$attrs = [
				'data-depth'    => $this->get_depth(),
				'data-name'     => CardGridPanel::FIELD_CARD_DESCRIPTION,
				'data-index'    => $index,
				'data-livetext' => true,
			];
		}

		$options = [
			Text::TEXT    => $text,
			Text::CLASSES => [ 'c-card__desc' ],
			Text::ATTRS   => $attrs,
		];

		$text_obj = Text::factory( $options );

		return $text_obj->render();
	}

	protected function get_card_button( $cta ) {
		if ( empty( $cta[ Button::URL ] ) ) {
			return '';
		}

		$options = [
			Button::URL         => esc_url( $cta[ Button::URL ] ),
			Button::LABEL       => esc_html( $cta[ Button::LABEL ] ),
			Button::TARGET      => esc_attr( $cta[ Button::TARGET ] ),
			Button::BTN_AS_LINK => true,
			Button::CLASSES     => [ 'c-btn--sm' ],
		];

		$button_obj = Button::factory( $options );

		return $button_obj->render();
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/cardgrid'];
	}
}
