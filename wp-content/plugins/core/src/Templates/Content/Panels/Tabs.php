<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Tabs as TabsPanel;
use Tribe\Project\Templates\Components\Tabs as TabsComponent;

class Tabs extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title'        => $this->get_title( $this->panel_vars[ TabsPanel::FIELD_TABS_TITLE ], [ 'section__title' ] ),
			'tabs'         => $this->get_tabs(),
			'grid_classes' => 'g-row--vertical-center',
		];

		return $data;
	}

	protected function get_tabs(): string {
		$options = [
			TabsComponent::TABS => $this->get_rows(),
		];

		$accordion = TabsComponent::factory( $options );

		return $accordion->render();
	}

	protected function get_rows(): array {
		$rows = $this->panel_vars[ TabsPanel::FIELD_TABS];

		if ( empty( $rows ) ) {
			return [];
		}

		return array_map( function ( $row ) {
			$header_id  = uniqid( 'tabs-header-' );
			$content_id = uniqid( 'tabs-content-' );

			return [
				'tab_id'      => $header_id,
				'content_id'  => $content_id,
				'tab_text'    => $row[ TabsPanel::FIELD_TABS_TITLE ],
				'content'     => $row[ TabsPanel::FIELD_TABS_CONTENT ],
			];
		}, $rows );
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/tabs'];
	}
}
