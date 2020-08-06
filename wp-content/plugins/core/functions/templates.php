<?php
if ( ! function_exists( 'tribe_template_part' ) ) {
	/**
	 * @param string $slug
	 * @param string $name
	 * @param array  $args
	 *
	 * @return false|string
	 */
	function tribe_template_part( $slug, $name = null, $args = [] ) {
		ob_start();
		get_template_part( $slug, $name, $args );

		return ob_get_clean();
	}
}

