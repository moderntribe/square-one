<?php

namespace Tribe\Project\Facade\Items\Theme;

use Tribe\Project\Facade\Facade;

class OEmbed extends Facade {

	/**
	 * This would return tribe_project()->container()['theme.ombed']
	 *
	 * @return string
	 */
	public static function get_container_key_accessor() {
		return 'theme.oembed';
	}
}
