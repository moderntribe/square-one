<?php

namespace Tribe\Project\Templates\Content\Panels;

use Tribe\Project\Panels\Types\Tabs as TabsPanel;
use Tribe\Project\Templates\Components\Tabs as TabsComponent;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Theme\Util;

class Tabs extends Panel {

	public function get_data(): array {
		$data       = parent::get_data();
		$panel_data = $this->get_mapped_panel_data();
		$data       = array_merge( $data, $panel_data );

		return $data;
	}

	public function get_mapped_panel_data(): array {
		$data = [
			'title' => $this->get_title( $this->panel_vars[ TabsPanel::FIELD_TITLE ], [ 'section__title' ] ),
			'tabs'  => $this->get_tabs(),
		];

		return $data;
	}

	protected function get_tabs(): string {
		$options = [
			TabsComponent::TABS           => $this->get_rows(),
			TabsComponent::TAB_LIST_ATTRS => [
				'data-depth'    => $this->panel->get_depth(),
				'data-name'     => 'tabs',
				'data-livetext' => 1,
			],
		];

		$tabs = TabsComponent::factory( $options );

		return $tabs->render();
	}

	protected function get_rows(): array {
		$rows = $this->panel_vars[ TabsPanel::FIELD_TABS ];
		if ( empty( $rows ) ) {
			return [];
		}
		return array_map( function ( $row, $index ) {
			$content_attrs = ( ! is_panel_preview() ) ? '' : Util::array_to_attributes([
				'data-depth'    => $this->panel->get_depth(),
				'data-index'    => $index,
				'data-name'     => 'row_content',
				'data-autop'    => 'true',
				'data-livetext' => 1,
			]);
			$btn_options   = ( ! is_panel_preview() ) ? [] : [
				Button::FORCE_DISPLAY    => true,
				Button::INNER_ATTRIBUTES => [
					'data-depth'    => $this->panel->get_depth(),
					'data-index'    => $index,
					'data-name'     => 'row_header',
					'data-livetext' => 1,
				],
			];
			return [
				'tab_id'        => uniqid( 'tabs-header-' ),
				'content_id'    => uniqid( 'tabs-content-' ),
				'tab_text'      => $row[ TabsPanel::FIELD_TABS_TITLE ],
				'content'       => $row[ TabsPanel::FIELD_TABS_CONTENT ],
				'content_attrs' => $content_attrs,
				'btn_options'   => $btn_options,
			];
		}, $rows, array_keys( $rows ) );
	}

	public static function instance() {
		return tribe_project()->container()['twig.templates.content/panels/tabs'];
	}
}
