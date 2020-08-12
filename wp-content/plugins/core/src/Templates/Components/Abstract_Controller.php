<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components;

abstract class Abstract_Controller {
	/**
	 * @param array $args
	 *
	 * @return static
	 */
	public static function factory( array $args = [] ) {
		return tribe_project()->container()->make( static::class, [ 'args' => $args ] );
	}
}
