<?php

namespace Tribe\Project\Templates\Controllers\Panels;

use Tribe\Project\Panels\Types\Wysiwyg as Wysi;
use Tribe\Project\Templates\Components\Panels\Wysiwyg as Wysiwyg_Context;

class Wysiwyg extends Panel {
	protected function render_content( \ModularContent\Panel $panel, array $panel_vars ): string {
		return $this->factory->get( Wysiwyg_Context::class, [
			Wysiwyg_Context::COLUMNS => $this->get_columns( $panel_vars ),
		])->render();
	}

	protected function get_columns( array $panel_vars ): array {
		return wp_list_pluck( $panel_vars[ Wysi::FIELD_COLUMNS ], Wysi::FIELD_COLUMN_CONTENT );
	}
}
