<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\LogoFarm as Logo;
use Tribe\Project\Templates\Components\Image;

class LogoFarm extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_title(): string {
		$title = '';

		if ( ! empty( $this->panel_vars[ Logo::FIELD_TITLE ] ) ) {
			$title = the_panel_title( esc_html( $this->panel_vars[ Logo::FIELD_TITLE ] ), 'section__title', 'title', true, 0, 0 );
		}

		return $title;
	}

	public function get_the_logos(): array {
		$logos = [];

		if ( ! empty( $this->panel_vars[ Logo::FIELD_LOGOS ] ) ) {
			for ( $i = 0; $i < count( $this->panel_vars[ Logo::FIELD_LOGOS ] ); $i++ ) {

				$logo = $this->panel_vars[ Logo::FIELD_LOGOS ][ $i ];

				$options = [
					Image::IMG_ID      => $logo[ Logo::FIELD_LOGO_IMAGE ],
					Image::LINK        => esc_url( $logo[ Logo::FIELD_LOGO_CTA ]['url'] ),
					Image::LINK_TARGET => esc_attr( $logo[ Logo::FIELD_LOGO_CTA ]['target'] ),
					Image::LINK_TITLE  => esc_attr( $logo[ Logo::FIELD_LOGO_CTA ]['label'] ),
				];

				$logo_obj = Image::factory( $options );
				$logos[]  = $logo_obj->render();
			}
		}

		return $logos;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'       => $this->get_title(),
			'description' => ! empty( $this->panel_vars[ Logo::FIELD_DESCRIPTION ] ) ? $this->panel_vars[ Logo::FIELD_DESCRIPTION ] : false,
			'logos'       => $this->get_the_logos(),
		];

		return $data;
	}
}