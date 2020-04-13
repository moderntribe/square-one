<?php

namespace Tribe\Project\Templates\Controllers\Panel;

use Tribe\Project\Panels\Types\MicroNavButtons as Micro;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Panels\Micronavbuttons as Micronavbuttons_Context;

class Micronavbuttons extends Panel {
	protected function render_content( \ModularContent\Panel $panel, array $panel_vars ): string {
		return $this->factory->get( Micronavbuttons_Context::class, [
			Micronavbuttons_Context::ITEMS      => $this->get_list_items( $panel_vars ),
			Micronavbuttons_Context::ATTRIBUTES => $this->get_micronavbutton_attributes( $panel ),
		] )->render();
	}

	protected function get_micronavbutton_attributes( \ModularContent\Panel $panel ): array {
		$attrs = [];

		if ( is_panel_preview() ) {
			$attrs['data-depth']    = $panel->get_depth();
			$attrs['data-name']     = Micro::FIELD_ITEMS;
			$attrs['data-index']    = '0';
			$attrs['data-livetext'] = 'true';
		}

		return $attrs;
	}

	protected function get_list_items( array $panel_vars ): array {
		$btns = [];

		foreach ( $panel_vars[ Micro::FIELD_ITEMS ] as $btn ) {
			$options = [
				Button::URL     => esc_url( $btn[ Micro::FIELD_ITEM_CTA ]['url'] ),
				Button::TARGET  => esc_attr( $btn[ Micro::FIELD_ITEM_CTA ]['target'] ),
				Button::LABEL   => esc_attr( $btn[ Micro::FIELD_ITEM_CTA ]['label'] ),
				Button::CLASSES => [ 'c-btn c-btn--full c-btn--lg' ],
			];

			$btns[] = $this->factory->get( Button::class, $options )->render();
		}

		return $btns;
	}
}
