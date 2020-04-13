<?php

namespace Tribe\Project\Templates\Controllers\Panels;

use Exception;
use Tribe\Project\Panels\Types\CardGrid as CardGridPanel;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Card;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Panels\Cardgrid as Cardgrid_Context;
use Tribe\Project\Templates\Components\Text;
use Tribe\Project\Theme\Config\Image_Sizes;

class Cardgrid extends Panel {
	protected function render_content( \ModularContent\Panel $panel, array $panel_vars ): string {
		return $this->factory->get( Cardgrid_Context::class, [
			Cardgrid_Context::CARDS      => $this->get_cards( $panel, $panel_vars ),
			Cardgrid_Context::ATTRIBUTES => $this->get_cardgrid_attributes( $panel ),
		] )->render();
	}

	protected function get_cards( \ModularContent\Panel $panel, array $panel_vars ): array {
		$depth = $panel->get_depth();

		$cards = [];

		if ( ! empty( $panel_vars[ CardGridPanel::FIELD_CARDS ] ) ) {

			$i = 0;

			foreach ( $panel_vars[ CardGridPanel::FIELD_CARDS ] as $card ) {
				$options = [];

				if ( ! empty( $card[ CardGridPanel::FIELD_CARD_TITLE ] ) ) {
					$options[ Card::TITLE ] = $this->get_card_title( $card[ CardGridPanel::FIELD_CARD_TITLE ], $i, $depth );
				}

				if ( ! empty( $card[ CardGridPanel::FIELD_CARD_DESCRIPTION ] ) ) {
					$options[ Card::TEXT ] = $this->get_card_text( $card[ CardGridPanel::FIELD_CARD_DESCRIPTION ], $i, $depth );
				}

				if ( ! empty( $card[ CardGridPanel::FIELD_CARD_IMAGE ] ) ) {
					$options[ Card::IMAGE ] = $this->get_card_image( $card[ CardGridPanel::FIELD_CARD_IMAGE ] );
				}

				if ( ! empty( $card[ CardGridPanel::FIELD_CARD_CTA ] ) ) {
					$options[ Card::BUTTON ] = $this->get_card_button( $card[ CardGridPanel::FIELD_CARD_CTA ], $card[ CardGridPanel::FIELD_CARD_TITLE ] );
				}

				$cards[] = $this->factory->get( Card::class, $options )->render();

				$i ++;
			}
		}

		return $cards;
	}

	protected function get_cardgrid_attributes( \ModularContent\Panel $panel ): array {
		$attrs = [];

		if ( is_panel_preview() ) {
			$attrs['data-depth']    = $panel->get_depth();
			$attrs['data-name']     = CardGridPanel::FIELD_CARDS;
			$attrs['data-index']    = '0';
			$attrs['data-livetext'] = 'true';
		}

		return $attrs;
	}

	protected function get_card_image( $image_id ) {
		if ( empty( $image_id ) ) {
			return '';
		}

		try {
			$image = \Tribe\Project\Templates\Models\Image::factory( $image_id );
		} catch ( Exception $e ) {
			return '';
		}

		$options = [
			Image::ATTACHMENT   => $image,
			Image::AS_BG        => false,
			Image::USE_LAZYLOAD => false,
			Image::SRC_SIZE     => Image_Sizes::COMPONENT_CARD,
		];

		$image_obj = $this->factory->get( Image::class, $options );

		return $image_obj->render();
	}

	protected function get_card_title( $title, $index, $depth ) {
		$attrs = [];

		if ( is_panel_preview() ) {
			$attrs = [
				'data-depth'    => $depth,
				'data-name'     => CardGridPanel::FIELD_CARD_TITLE,
				'data-index'    => $index,
				'data-livetext' => true,
			];
		}

		$options = [
			Text::TEXT    => $title,
			Text::CLASSES => [ 'c-card__title' ],
			Text::ATTRS   => $attrs,
			Text::TAG     => 'h3',
		];

		$title_obj = $this->factory->get( Text::class, $options );

		return $title_obj->render();
	}

	protected function get_card_text( $text, $index, $depth ) {
		$attrs = [];

		if ( is_panel_preview() ) {
			$attrs = [
				'data-depth'    => $depth,
				'data-name'     => CardGridPanel::FIELD_CARD_DESCRIPTION,
				'data-index'    => $index,
				'data-autop'    => 'true',
				'data-livetext' => true,
			];
		}

		$options = [
			Text::TEXT    => $text,
			Text::CLASSES => [ 'c-card__desc', 't-content' ],
			Text::ATTRS   => $attrs,
		];

		$text_obj = $this->factory->get( Text::class, $options );

		return $text_obj->render();
	}

	protected function get_card_button( $cta, $aria_label ) {
		if ( empty( $cta[ Button::URL ] ) ) {
			return '';
		}

		$options = [
			Button::URL         => esc_url( $cta[ Button::URL ] ),
			Button::LABEL       => esc_html( $cta[ Button::LABEL ] ),
			Button::TARGET      => esc_attr( $cta[ Button::TARGET ] ),
			Button::BTN_AS_LINK => true,
			Button::CLASSES     => [ 'c-btn c-btn--sm' ],
			Button::ARIA_LABEL  => $aria_label,
		];

		$button_obj = $this->factory->get( Button::class, $options );

		return $button_obj->render();
	}
}
