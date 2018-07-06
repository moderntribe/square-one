<?php

namespace Tribe\Project\Facade\Items;

use Tribe\Project\Facade\Facade;

class Request extends Facade {

	public static function get_container_key_accessor() {
		return 'request';
	}
}