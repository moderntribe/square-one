<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Gallery as GalleryPanel;

class Gallery extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_title(): string {
		$title = '';

		if ( ! empty( $this->panel_vars[ GalleryPanel::FIELD_TITLE ] ) ) {
			$title = the_panel_title( esc_html( $this->panel_vars[ GalleryPanel::FIELD_TITLE ] ), 'section__title', 'title', true, 0, 0 );
		}

		return $title;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'       => $this->get_title(),
		];

		return $data;
	}
}
