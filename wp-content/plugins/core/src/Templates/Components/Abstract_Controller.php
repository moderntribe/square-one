<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components;

abstract class Abstract_Controller {

	/**
	 * Merge the passed arguments with the default and
	 * required arguments defined by the controller
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	protected function parse_args( array $args ): array {
		$args = wp_parse_args( $args, $this->defaults() );

		foreach ( $this->required() as $key => $value ) {
			if ( ! is_array( $value ) ) {
				throw new \UnexpectedValueException( __( 'Required arguments should be of the type array', 'tribe' ) );
			}
			$args[ $key ] = array_merge( $args[ $key ], $value );
		}

		return $args;
	}

	/**
	 * Default arguments are merged with the passed
	 * arguments to fill in empty values
	 *
	 * @return array
	 */
	protected function defaults(): array {
		return [];
	}

	/**
	 * Required arguments should be arrays of values
	 * that will be merged with values passed in args.
	 *
	 * E.g., if a controller accepts optional classes,
	 * but should also _always_ have a 'special' class
	 * appended, this would return:
	 * [ 'classes' => [ 'special' ] ]
	 *
	 * @return array[]
	 */
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
