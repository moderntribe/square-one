<?php declare(strict_types=1);

namespace Tribe\Project\Settings;

use Tribe\Libs\ACF\ACF_Settings;

class Post_Settings extends ACF_Settings {

	public function get_title() {
		return __( 'Settings', 'tribe' );
	}

	public function get_capability() {
		return 'activate_plugins';
	}

	public function get_parent_slug() {
		return 'edit.php';
	}

}
