<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Panels\Traits;

use ModularContent\Panel;

/**
 * Trait Unwrapped
 *
 * Causes the panel to render without the "<div class='l-container'>" wrapper
 */
trait Unwrapped {
	protected function use_wrapper( Panel $panel ): bool {
		return false;
	}
}
