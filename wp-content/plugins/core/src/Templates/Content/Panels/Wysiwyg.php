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
			$title = the_panel_title( esc_html( $this->panel_vars[ Wysi::FIELD_TITLE ] ), 'section__title', 'title', true, 0, 0 );
		}

		return $title;
	}

	public function get_the_columns(): array {
		$columns = [];

		if ( ! empty( $this->panel_vars[ Wysi::FIELD_COLUMNS ] ) ) {
			foreach ( $this->panel_vars[ Wysi::FIELD_COLUMNS ] as $col ) {

				$columns[] = [
					'content'         => $col[ Wysi::FIELD_COLUMN_CONTENT ],
					'wysiwyg_classes' => $this->get_wysiwyg_classes(),
					'wysiwyg_attrs'   => $this->get_wysiwyg_attrs(),
				];
			}
		}

		return $columns;
	}

	protected function get_wysiwyg_attrs() {
		$content_attrs = sprintf( 'data-depth="0" data-name="%s" data-index="%s" data-autop="true" data-livetext',
			esc_attr( Wysi::FIELD_COLUMN_CONTENT ),
			esc_attr( get_nest_index() )
		);

		return $content_attrs;
	}

	protected function get_wysiwyg_classes() {

		$classes = [ 'c-wysiwyg' ];

		if ( ! empty( Wysi::NAME ) ) {
			$classes[] = 't-content';
		}

		return implode( ' ', $classes );
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
