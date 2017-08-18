<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Testimonial as Test;
use Tribe\Project\Templates\Components\Quote;

class Testimonial extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_title(): string {
		$title = '';

		if ( ! empty( $this->panel_vars[ Test::FIELD_TITLE ] ) ) {
			$title = the_panel_title( esc_html( $this->panel_vars[ Test::FIELD_TITLE ] ), 'site-section__title h5', 'title', true, 0, 0 );
		}

		return $title;
	}

	public function get_the_quotes(): array {
		$quotes = [];

		if ( ! empty( $this->panel_vars[ Test::FIELD_QUOTES ] ) ) {
			for ( $i = 0; $i < count( $this->panel_vars[ Test::FIELD_QUOTES ] ); $i++ ) {

				$quote       = $this->panel_vars[ Test::FIELD_QUOTES ][ $i ];
				$quote_attrs = [];
				$cite_attrs  = [];

				if ( is_panel_preview() ) {
					$quote_attrs = [
						'data-depth'    => $this->panel->get_depth(),
						'data-name'     => Test::FIELD_QUOTE,
						'data-index'    => $i,
						'data-autop'    => 'true',
						'data-livetext' => true,
					];

					$cite_attrs = [
						'data-depth'    => $this->panel->get_depth(),
						'data-name'     => Test::FIELD_CITE,
						'data-index'    => $i,
						'data-livetext' => true,
					];
				}

				$options = [
					Quote::QUOTE       => $quote[ Test::FIELD_QUOTE ],
					Quote::CITE        => $quote[ Test::FIELD_CITE ],
					Quote::QUOTE_ATTRS => $quote_attrs,
					Quote::CITE_ATTRS  => $cite_attrs,
				];

				$quote_obj = Quote::factory( $options );
				$quotes[]  = $quote_obj->render();
			}
		}

		return $quotes;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'  => $this->get_title(),
			'quotes' => $this->get_the_quotes(),
		];

		return $data;
	}
}