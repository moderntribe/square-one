<?php
/**
 * Shortcode Interface
 *
 * Provides an interface for custom shortcode registration.
 */

namespace Tribe\Project\Shortcodes;

interface Shortcode {

	/**
	 * Return the rendered markup for this shortcode.
	 *
	 * @return string
	 */
	public function render( array $attr, int $instance ): string;
}
