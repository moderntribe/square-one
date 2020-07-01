<?php
declare( strict_types=1 );

namespace Tribe\Project\Components;

use Tribe\Libs\Container\Abstract_Subscriber;
use Tribe\Libs\Utils\Markup_Utils;
use Twig\TwigFilter;
use Twig\TwigFunction;

class Components_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		add_filter( 'tribe/libs/twig/funcitons', function ( $fs ) {
			$handler = $this->container->get( Handler::class );
			$fs[]    = new TwigFunction( 'component', [ $handler, 'render_component' ] );

			return $fs;
		} );

		add_filter( 'tribe/libs/twig/filters', function ( $fs ) {
			$fs[]    = new TwigFilter( 'stringify', [ $this, 'merge_array_values' ] );

			return $fs;
		} );
	}

	/**
	 * Merge associative arrays of attributes or classes into a string
	 *
	 * @param array ...$attributes
	 *
	 * @return string
	 */
	public function merge_array_values( array ...$attributes ): string {
		$attributes = empty( $attributes ) ? [] : array_merge( ... $attributes );

		if ( is_numeric( array_key_first( $attributes ) ) ) {
			return Markup_Utils::class_attribute( $attributes, true );
		}

		return Markup_Utils::concat_attrs( $attributes );
	}


}
