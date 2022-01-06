<?php declare(strict_types=1);

use Tribe\Project\Templates\Components\Deferred_Component;

if ( ! function_exists( 'tribe_template_part' ) ) {
	/**
	 * @param string      $slug
	 * @param string|null $name
	 * @param array       $args
	 *
	 * @return string
	 */
	function tribe_template_part( string $slug, ?string $name = null, array $args = [] ): string {
		ob_start();

		get_template_part( $slug, $name, $args );

		return (string) ob_get_clean();
	}
}

if ( ! function_exists( 'defer_template_part' ) ) {
	/**
	 * @param string      $slug
	 * @param string|null $name
	 * @param array       $args
	 *
	 * @return \Tribe\Project\Templates\Components\Deferred_Component
	 */
	function defer_template_part( string $slug, ?string $name = null, array $args = [] ): Deferred_Component {
		return new Deferred_Component( $slug, $name, $args );
	}
}
