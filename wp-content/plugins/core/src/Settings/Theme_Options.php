<?php declare(strict_types=1);

namespace Tribe\Project\Settings;

use Tribe\Libs\ACF\ACF_Settings;

class Theme_Options extends ACF_Settings {

	public const SLUG = 'theme-options';

	public function get_title(): string {
		$current_theme = wp_get_theme();
		$theme_name    = $current_theme->exists() ? $current_theme->get( 'Name' ) : 'Theme';

		return sprintf( esc_html__( '%s Options', 'tribe' ), $theme_name );
	}

	public function get_capability(): string {
		return 'manage_options';
	}

	public function get_parent_slug(): string {
		return 'themes.php';
	}

	protected function set_slug(): void {
		$this->slug = sanitize_title( self::SLUG );
	}

}
