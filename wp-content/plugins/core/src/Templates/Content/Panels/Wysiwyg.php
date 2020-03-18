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

	public function get_mapped_panel_data(): array {
		$data = [
			'title'   => $this->get_title( $this->panel_vars[ Wysi::FIELD_TITLE ], [ 's-title', 'h2' ] ),
			'columns' => $this->get_the_columns(),
		];

		return $data;
	}

	protected function get_the_columns(): array {
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

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/wysiwyg'];
	}
}
