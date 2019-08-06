<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Wysiwyg as Wysi;
use Tribe\Project\Theme\Util;

class Wysiwyg extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'           => $this->get_title( $this->panel_vars[ Wysi::FIELD_TITLE ], [ 's-title', 'h2' ] ),
			'header_classes'  => $this->get_layout_classes(),
			'wrapper_classes' => $this->get_wrapper_classes(),
			'columns'         => $this->get_the_columns(),
		];

		return $data;
	}

	protected function get_layout_classes() {
		$classes = [];

		if ( ! empty( $this->panel_vars[ Wysi::FIELD_LAYOUT ] ) ) {
			$classes[] = 's-header--' . $this->panel_vars[ Wysi::FIELD_LAYOUT ];
		}

		return Util::class_attribute( $classes, false );
	}

	protected function get_wrapper_classes() {
		$classes = [];
		$columns = $this->panel_vars[ Wysi::FIELD_COLUMNS ];

		if ( ! empty( $columns ) && count( $columns ) >= 3 ) {
			$classes[] = 'g-row--col-3--min-full';
		}

		if ( ! empty( $columns ) && count( $columns ) === 2 ) {
			$classes[] = 'g-row--col-2--min-full';
		}

		return Util::class_attribute( $classes, false );
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
