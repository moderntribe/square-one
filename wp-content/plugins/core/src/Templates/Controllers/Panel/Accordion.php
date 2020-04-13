<?php

namespace Tribe\Project\Templates\Controllers\Panel;

use Tribe\Project\Panels\Types\Accordion as AccordionPanel;
use Tribe\Project\Templates\Components\Accordion as AccordionComponent;
use Tribe\Project\Templates\Components\Panels\Accordion as Accordion_Context;

class Accordion extends Panel {

	protected function render_content( \ModularContent\Panel $panel, array $panel_vars ): string {
		return $this->factory->get( Accordion_Context::class, [
			Accordion_Context::LAYOUT      => $panel_vars[ AccordionPanel::FIELD_LAYOUT ] ?? AccordionPanel::FIELD_LAYOUT_OPTION_RIGHT,
			Accordion_Context::ACCORDION   => $this->get_accordion( $panel_vars ),
			Accordion_Context::GRID_CASSES => $this->get_grid_classes( $panel_vars ),
			Accordion_Context::ATTRIBUTES  => $this->get_accordion_attributes( $panel ),
		] )->render();
	}

	protected function get_accordion_attributes( \ModularContent\Panel $panel ): array {
		$attrs = [];

		if ( is_panel_preview() ) {
			$attrs['data-depth']    = $panel->get_depth();
			$attrs['data-name']     = AccordionPanel::FIELD_ACCORDIONS;
			$attrs['data-index']    = '0';
			$attrs['data-livetext'] = 'true';
		}

		return $attrs;
	}

	protected function get_grid_classes( array $panel_vars ): array {
		$classes = [ 'g-row--vertical-center' ];

		if ( ! empty( $panel_vars[ AccordionPanel::FIELD_LAYOUT ] ) && $panel_vars[ AccordionPanel::FIELD_LAYOUT ] !== AccordionPanel::FIELD_LAYOUT_OPTION_CENTER ) {
			$classes[] = 'g-row--col-2--min-full';
		}

		return $classes;
	}

	protected function get_accordion( array $panel_vars ): string {
		$options = [
			AccordionComponent::ROWS => $this->get_rows( $panel_vars ),
		];

		$accordion = $this->factory->get( AccordionComponent::class, $options );

		return $accordion->render();
	}

	protected function get_rows( array $panel_vars ): array {
		$rows = $panel_vars[ AccordionPanel::FIELD_ACCORDIONS ];

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
