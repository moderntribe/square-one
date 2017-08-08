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

	protected function get_logo( $logo ): string {

		if ( empty( $logo ) ) {
			return '';
		}

		$options = [
			'component_class' => 'c-image',
			'as_bg'           => true,
			'use_lazyload'    => false,
			'echo'            => false,
		];

		$image_obj = Image::factory( $logo, $options );

		return $image_obj->render();
	}

	public function get_the_logos(): array {
		$logos = [];

		if ( ! empty( $this->panel_vars[ Logo::FIELD_LOGOS ] ) ) {

			foreach ( $this->panel_vars[ Logo::FIELD_LOGOS ] as $logo ) {

				$logos[] = [
					'logo'        => $logo[ Logo::FIELD_LOGO_IMAGE ],
					'logo_url'    => $logo[ Logo::FIELD_LOGO_CTA ]['url'],
					'logo_target' => $logo[ Logo::FIELD_LOGO_CTA ]['target'],
				];
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