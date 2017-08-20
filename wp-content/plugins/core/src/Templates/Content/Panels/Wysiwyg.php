<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Wysiwyg as Wysi;

class Wysiwyg extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_title(): string {
		$title = '';

		if ( ! empty( $this->panel_vars[ Wysi::FIELD_TITLE ] ) ) {
			$title = the_panel_title( esc_html( $this->panel_vars[ Wysi::FIELD_TITLE ] ), 'site-section__title h2', 'title', true, 0, 0 );
		}

		return $title;
	}

	public function get_the_columns(): array {
		$columns = [];

		if ( ! empty( $this->panel_vars[ Wysi::FIELD_COLUMNS ] ) ) {
			foreach ( $this->panel_vars[ Wysi::FIELD_COLUMNS ] as $col ) {

				$columns[] = [
					'content' => $col[ Wysi::FIELD_COLUMN_CONTENT ],
				];
			}
		}

		return $columns;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'       => $this->get_title(),
			'description' => ! empty( $this->panel_vars[ Wysi::FIELD_DESCRIPTION ] ) ? $this->panel_vars[ Wysi::FIELD_DESCRIPTION ] : false,
			'columns'     => $this->get_the_columns(),
		];

		return $data;
	}
}
