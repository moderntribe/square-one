<?php
declare( strict_types=1 );

namespace Tribe\Project\Components;

use Tribe\Libs\Container\Abstract_Subscriber;
use Tribe\Libs\Utils\Markup_Utils;
use Twig\TwigFunction;

class Components_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		add_filter( 'tribe/libs/twig/funcitons', function ( $fs ) {
			$handler = $this->container->get( Handler::class );
			$fs[]    = new TwigFunction( 'component', [ $handler, 'render_component' ] );

			return $fs;
		} );

		add_filter( 'tribe/libs/twig/funcitons', function ( $fs ) {
			$fs[]    = new TwigFunction( 'attributes', [ $this, 'merge_attrs' ] );
			$fs[]    = new TwigFunction( 'classes', [ $this, 'merge_classes' ] );

			return $fs;
		} );
	}

	/**
	 * Merge associative arrays of attributes into a string
	 *
	 * @param array ...$attributes
	 *
	 * @return string
	 */
	public function merge_attrs( array ...$attributes ): string {
		$attributes = empty( $attributes ) ? [] : array_merge( ... $attributes );

		return Markup_Utils::concat_attrs( $attributes );
	}

	/**
	 * Sanitize and merge classes into a string for inclusion in a class attribute
	 *
	 * @param array ...$classes
	 *
	 * @return string
	 */
	public function merge_classes( array ...$classes ): string {
		return Markup_Utils::class_attribute( array_merge( ... $classes ), true );
	}


}
