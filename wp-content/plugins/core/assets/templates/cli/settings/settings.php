<?php

namespace Tribe\Project\Settings;

use Tribe\Libs\ACF\ACF_Settings;

class % 1$s extends ACF_Settings {

	public function get_title() {
		return '%2$s';
	}

	public function get_capability() {
		return 'activate_plugins';
	}

	public function get_parent_slug() {
		return 'options-general.php';
	}

	public static function instance() {
		return tribe_project()->container()['settings.%3$s'];
	}
}
