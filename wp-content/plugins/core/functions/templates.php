<?php

use Tribe\Project\Templates\Components\Deferred_Component;

if ( ! function_exists( 'tribe_template_part' ) ) {
	/**
	 * @param string      $slug
	 * @param string|null $name
	 * @param array       $args
	 *
	 * @return false|string
	 */
	function tribe_template_part( $slug, $name = null, $args = [] ) {
		ob_start();
		get_template_part( $slug, $name, $args );

		return ob_get_clean();
	}
}

if ( ! function_exists( 'defer_template_part' ) ) {
	/**
	 * @param string      $slug
	 * @param string|null $name
	 * @param array       $args
	 *
	 * @return Deferred_Component
	 */
	function defer_template_part( $slug, $name = null, $args = [] ) {
		return new Deferred_Component( $slug, $name, $args );
	}
}
