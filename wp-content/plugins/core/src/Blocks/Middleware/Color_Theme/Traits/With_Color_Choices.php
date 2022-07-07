<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme\Traits;

/**
 * @mixin \Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Appearance
 */
trait With_Color_Choices {

	/**
	 * The default choices for the color theme's acf-swatch field.
	 *
	 * @return array<string, string>
	 */
	protected function get_color_theme_choices(): array {
		return [
			self::THEME_WHITE      => esc_html__( 'White', 'tribe' ),
			self::THEME_LIGHT_GRAY => esc_html__( 'Light Gray', 'tribe' ),
		];
	}

}
