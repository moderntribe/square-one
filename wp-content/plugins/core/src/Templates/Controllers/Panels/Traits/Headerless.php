<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Panels\Traits;

/**
 * Trait Headerless
 *
 * Cause the panel to render without a header, due to the lack of title and description
 */
trait Headerless {
	protected function get_title( array $panel_vars ): string {
		return '';
	}

	protected function get_description( array $panel_vars ): string {
		return '';
	}
}
