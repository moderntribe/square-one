<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Accordion as AccordionPanel;
use Tribe\Project\Templates\Components\Accordion as AccordionComponent;
use Tribe\Project\Theme\Util;

class Accordion extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_title(): string {
		$title = '';

		if ( ! empty( $this->panel_vars[ AccordionPanel::FIELD_ACCORDION_TITLE ] ) ) {
			$title = the_panel_title( esc_html( $this->panel_vars[ AccordionPanel::FIELD_ACCORDION_TITLE ] ), 'section__title', 'title', true, 0, 0 );
		}

		return $title;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'        => $this->get_title(),
			'content'      => $this->panel_vars[AccordionPanel::FIELD_CONTENT],
			'layout'       => $this->panel_vars[AccordionPanel::FIELD_LAYOUT],
			'accordion'    => $this->get_accordion(),
			'grid_classes' => $this->get_grid_classes(),
		];

		return $data;
	}

	protected function get_grid_classes(): string {
		$classes = 'site-grid__layout site-grid__layout--center';

		if ( ! empty( $this->panel_vars[ AccordionPanel::FIELD_LAYOUT ] ) && $this->panel_vars[ AccordionPanel::FIELD_LAYOUT ] !== AccordionPanel::FIELD_LAYOUT_OPTION_CENTER ) {
			$classes .= ' site-grid__layout--2-col-full';
		}

		return $classes;
	}

	protected function get_accordion(): string {
		$options = [
			AccordionComponent::ROWS => $this->get_rows(),
		];

		$accordion = AccordionComponent::factory( $options );

		return $accordion->render();
	}

	protected function get_rows(): array {
		$rows = $this->panel_vars[ AccordionPanel::FIELD_ACCORDIONS ];

		if ( empty( $rows ) ) {
			return [];
		}

		return array_map( function ( $row ) {
			$header_id  = uniqid( 'accordion-header-' );
			$content_id = uniqid( 'accordion-content-' );

			return [
				'header_id'   => $header_id,
				'content_id'  => $content_id,
				'header_text' => $row[ AccordionPanel::FIELD_ACCORDION_TITLE ],
				'content'     => $row[ AccordionPanel::FIELD_ACCORDION_CONTENT ],
			];
		}, $rows );
	}
}
