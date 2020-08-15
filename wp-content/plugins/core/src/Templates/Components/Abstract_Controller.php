<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components;

abstract class Abstract_Controller {

	protected function parse_args( array $args ): array {
		$args = wp_parse_args( $args, $this->defaults() );

		foreach ( $this->required() as $key => $value ) {
			$args[$key] = array_merge( $args[$key], $value );
		}

		return $args;
	}

	protected function defaults(): array {
		return [];
	}

	protected function required(): array {
		return [];
	}

	/**
	 * @param array $args
	 *
	 * @return static
	 */
	public static function factory( array $args = [] ) {
		return tribe_project()->container()->make( static::class, [ 'args' => $args ] );
	}
}
