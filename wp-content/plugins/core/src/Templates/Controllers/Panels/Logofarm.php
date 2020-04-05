<?php

namespace Tribe\Project\Templates\Controllers\Panels;

use Tribe\Project\Panels\Types\LogoFarm as Logo;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Templates\Components\Panels\Logofarm as Logofarm_Context;

class Logofarm extends \Tribe\Project\Templates\Controllers\Panels\Panel {
	protected function render_content( \ModularContent\Panel $panel, array $panel_vars ): string {
		return $this->factory->get( Logofarm_Context::class, [
			Logofarm_Context::LOGOS      => $this->get_the_logos( $panel_vars ),
			Logofarm_Context::ATTRIBUTES => $this->get_logofarm_attributes( $panel ),
		] )->render();
	}

	protected function get_logofarm_attributes( \ModularContent\Panel $panel ): array {
		$attrs = [];

		if ( is_panel_preview() ) {
			$attrs['data-depth']    = $panel->get_depth();
			$attrs['data-name']     = Logo::FIELD_LOGOS;
			$attrs['data-index']    = '0';
			$attrs['data-livetext'] = 'true';
		}

		return $attrs;
	}

	protected function get_the_logos( array $panel_vars ): array {
		$logos = [];

		foreach ( $panel_vars[ Logo::FIELD_LOGOS ] as $logo ) {

			try {
				$image = \Tribe\Project\Templates\Models\Image::factory( $logo[ Logo::FIELD_LOGO_IMAGE ] );
			} catch ( \Exception $e ) {
				continue;
			}

			$options = [
				Image::ATTACHMENT  => $image,
				Image::LINK_URL    => $logo[ Logo::FIELD_LOGO_CTA ]['url'],
				Image::LINK_TARGET => $logo[ Logo::FIELD_LOGO_CTA ]['target'],
				Image::LINK_TITLE  => $logo[ Logo::FIELD_LOGO_CTA ]['label'],
			];

			$logos[] = $this->factory->get( Image::class, $options )->render();
		}

		return $logos;
	}
}
