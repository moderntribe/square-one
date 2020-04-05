<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Panels\Traits;

/**
 * Trait Unwrapped
 *
 * Causes the panel to render without the "<div class='l-container'>" wrapper
 */
trait Unwrapped {
	protected function use_wrapper( \ModularContent\Panel $panel ): bool {
		return false;
	}
}
