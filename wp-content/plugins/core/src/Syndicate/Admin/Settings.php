<?php

namespace Tribe\Project\Syndicate\Admin;

use Tribe\Libs\ACF\ACF_Settings;

class Settings extends ACF_Settings {

	public function get_title() {
		return 'Syndication Settings';
	}

	public function get_capability() {
		return 'activate_plugins';
	}

	public function get_parent_slug() {
		return 'options-general.php';
	}

	public static function instance() {
		return tribe_project()->container()['syndication.settings'];
	}

	public function hook( $priority = 10 ) {
		if ( ! is_main_site() ) {
			return;
		}

		return parent::hook( $priority );
	}
}