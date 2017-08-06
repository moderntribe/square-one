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

	public function get_title( $panel ): string {
		$title = '';

		if ( ! empty( $panel[ Wysi::FIELD_TITLE ] ) ) {
			$title = the_panel_title( esc_html( $panel[ Wysi::FIELD_TITLE ] ), 'section__title', 'title', true, 0, 0 );
		}

		return $title;
	}

	public function get_the_columns( $panel ): array {
		$columns = [];

		if ( ! empty( $panel[ Wysi::FIELD_COLUMNS ] ) ) {
			foreach ( $panel[ Wysi::FIELD_COLUMNS ] as $col ) {
				$content = $col[ Wysi::FIELD_COLUMN_CONTENT ];

				$columns[] = [
					'content' => $content,
				];
			}
		}

		return $columns;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'   => $this->get_title( $this->panel_vars ),
			'content' => ! empty( $this->panel_vars[ Wysi::FIELD_CONTENT ] ) ? $this->panel_vars[ Wysi::FIELD_CONTENT ] : false,
			'columns' => $this->get_the_columns( $this->panel_vars ),
		];

		return $data;
	}
}
