<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates;

trait Factory_Method {
	/**
	 * @return static
	 */
	public static function factory() {
		return tribe_project()->container()->get( static::class );
	}
}
