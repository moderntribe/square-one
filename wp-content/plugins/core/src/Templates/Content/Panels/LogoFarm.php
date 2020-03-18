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

	public function get_mapped_panel_data(): array {
		$data = [
			'title' => $this->get_title( $this->panel_vars[ Logo::FIELD_TITLE ], [ 's-title', 'h2' ] ),
			'logos' => $this->get_the_logos(),
			'attrs' => $this->get_logofarm_attributes(),
		];

		return $data;
	}

	protected function get_logofarm_attributes() {
		$attrs = '';

		if ( is_panel_preview() ) {
			$attrs = 'data-depth=' . $this->panel->get_depth() . ' data-name="' . Logo::FIELD_LOGOS . '" data-index="0" data-livetext="true"';
		}

		if ( empty( $attrs ) ) {
			return '';
		}

		return $attrs;
	}

	protected function get_the_logos(): array {
		$logos = [];

		if ( ! empty( $this->panel_vars[ Logo::FIELD_LOGOS ] ) ) {
			for ( $i = 0; $i < count( $this->panel_vars[ Logo::FIELD_LOGOS ] ); $i ++ ) {
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

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/logofarm'];
	}
}
