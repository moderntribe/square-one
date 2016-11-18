<?php

namespace Tribe\Project\Theme;

use ModularContent\Panel;

class Panel_Util {

	/**
	 * @param Panel $panel
	 * @param array $classes
	 *
	 * @return string
	 */
	public function wrapper_classes( Panel $panel, $classes = [] ) {
		$type = $panel->get( 'type' );

		$classes[] = sprintf( 'panel--type-%s', esc_attr( $type ) );

		return sprintf( ' class="%s"', implode( ' ', array_unique( $classes ) ) );
	}

}
