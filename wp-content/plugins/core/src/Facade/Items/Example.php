<?php

namespace Tribe\Project\Facade\Items;

use Tribe\Project\Facade\Facade;

class Example extends Facade {

	/**
	 * This would return tribe_project()->container()['example']
	 *
	 * @return string
	 */
	public static function get_container_key_accessor() {
		return 'example';
	}
}