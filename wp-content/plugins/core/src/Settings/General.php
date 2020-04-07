<?php

namespace Tribe\Project\Settings;

use Tribe\Libs\ACF\ACF_Settings;

class General extends ACF_Settings {

	public function get_title() {
		return 'General Settings';
	}

	public function get_capability() {
		return 'activate_plugins';
	}

	public function get_parent_slug() {
		return 'options-general.php';
	}

}
