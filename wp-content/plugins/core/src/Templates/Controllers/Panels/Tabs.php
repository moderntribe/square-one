<?php

namespace Tribe\Project\Templates\Controllers\Panels;

use Tribe\Project\Panels\Types\Tabs as TabsPanel;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Panels\Tabs as Tabs_Context;
use Tribe\Project\Templates\Components\Tabs as TabsComponent;

class Tabs extends Panel {
	protected function render_content( \ModularContent\Panel $panel, array $panel_vars ): string {
		return $this->factory->get( Tabs_Context::class, [
			Tabs_Context::TABS => $this->get_tabs( $panel, $panel_vars ),
		] )->render();
	}

	protected function get_tabs( \ModularContent\Panel $panel, array $panel_vars ): string {
		$options = [
			TabsComponent::TABS           => $this->get_rows( $panel, $panel_vars ),
			TabsComponent::TAB_LIST_ATTRS => [
				'data-depth'    => $panel->get_depth(),
				'data-name'     => 'tabs',
				'data-livetext' => 1,
			],
		];

		return $this->factory->get( TabsComponent::class, $options )->render();
	}

	protected function get_rows( \ModularContent\Panel $panel, array $panel_vars ): array {
		$depth = $panel->get_depth();
		$rows  = $panel_vars[ TabsPanel::FIELD_TABS ];
		if ( empty( $rows ) ) {
			return [];
		}

		return array_map( function ( $row, $index ) use ( $depth ) {
			$content_attrs = ( ! is_panel_preview() ) ? [] : [
				'data-depth'    => $depth,
				'data-index'    => $index,
				'data-name'     => 'row_content',
				'data-autop'    => 'true',
				'data-livetext' => 1,
			];
			$btn_options   = ( ! is_panel_preview() ) ? [] : [
				Button::FORCE_DISPLAY    => true,
				Button::INNER_ATTRIBUTES => [
					'data-depth'    => $depth,
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
				'btn_attrs'     => $btn_options,
			];
		}, $rows, array_keys( $rows ) );
	}
}
