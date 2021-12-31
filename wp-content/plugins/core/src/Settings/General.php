<?php declare(strict_types=1);

namespace Tribe\Project\Settings;

use Tribe\Libs\ACF\ACF_Settings;

class General extends ACF_Settings {

	public function get_title(): string {
		return __( 'General Settings', 'tribe' );
	}

	public function get_capability(): string {
		return 'activate_plugins';
	}

	public function get_parent_slug(): string {
		return 'options-general.php';
	}

}
