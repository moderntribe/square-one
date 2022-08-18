<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta;

use Tribe\Project\Object_Meta\Contracts\Abstract_Settings_With_Tab;

class Theme_Setting_Tabs extends Abstract_Settings_With_Tab {

	public const NAME = 'theme_options';

	public function get_key(): string {
		return self::NAME;
	}

	public function get_title(): string {
		return esc_html__( 'Theme Options', 'tribe' );
	}

}
