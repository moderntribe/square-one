<?php declare(strict_types=1);

namespace Tribe\Project\Templates\Components;

use UnexpectedValueException;

/**
 * Base class for other controllers to extend.
 */
abstract class Abstract_Controller {

	/**
	 * @param array $args
	 *
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 *
	 * @return \Tribe\Project\Templates\Components\Abstract_Controller
	 */
	public static function factory( array $args = [] ): Abstract_Controller {
		return tribe_project()->container()->make( static::class, [ 'args' => $args ] );
	}

	/**
	 * Merge the passed arguments with the default and
	 * required arguments defined by the controller.
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	protected function parse_args( array $args ): array {
		$args = wp_parse_args( $args, $this->defaults() );

		foreach ( $this->required() as $key => $value ) {
			if ( ! is_array( $value ) ) {
				throw new UnexpectedValueException( esc_html__( 'Required arguments should be of the type array', 'tribe' ) );
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

}
