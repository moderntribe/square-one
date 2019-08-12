<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Wysiwyg as Wysi;
use Tribe\Project\Theme\Util;

class Wysiwyg extends Panel {

	/**
	 * Get the data.
	 *
	 * @return array
	 */
	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	/**
	 * Get the mapped panel data.
	 *
	 * @return array
	 */
	public function get_mapped_panel_data(): array {
		$data = [
			'title'           => $this->get_title( $this->panel_vars[ Wysi::FIELD_TITLE ], [ 's-title', 'h2' ] ),
			'wrapper_classes' => $this->get_wrapper_classes(),
			'columns'         => $this->get_the_columns(),
		];

		return $data;
	}

	/**
	 * Overrides `get_classes()` from the Panel parent class.
	 *
	 * Return value is available in the twig template via the `classes` twig variable in the parent class.
	 *
	 * @return string
	 */
	protected function get_classes(): string {
		$wrapper_class = Wysi::OPTION_LAYOUT_LEFT === $this->panel_vars[ Wysi::FIELD_LAYOUT ] ? 'site-panel--wysiwyg__layout-left' : 'site-panel--wysiwyg__layout-center';
		$classes       = [
			'panel',
			's-wrapper',
			'site-panel',
			sprintf( 'site-panel--%s', $this->panel->get_type_object()->get_id() ),
			$wrapper_class,
		];
		return Util::class_attribute( $classes );
	}

	/**
	 * Get WYSIWYG row wrapper classes.
	 *
	 * @return string
	 */
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

	/**
	 * Get the columns data.
	 *
	 * @return array
	 */
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

	/**
	 * Return instance.
	 * @return mixed
	 */
	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/wysiwyg'];
	}
}
