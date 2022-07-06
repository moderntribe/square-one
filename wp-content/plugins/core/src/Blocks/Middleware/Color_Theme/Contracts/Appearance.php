<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme\Contracts;

/**
 * The color theme field's hex color choices.
 *
 * If you adjust these, make sure you adjust the map in the definer and the
 * color choices trait.
 *
 * @see \Tribe\Project\Blocks\Middleware\Color_Theme\Color_Theme_Definer::define()
 * @see \Tribe\Project\Blocks\Middleware\Color_Theme\Traits\With_Color_Choices::get_color_theme_choices()
 */
interface Appearance {

	public const COLOR_THEME = 'color_theme';

	public const THEME_WHITE      = '#FFF';
	public const THEME_LIGHT_GRAY = '#F0F0F0';
	public const THEME_DEFAULT    = self::THEME_WHITE;

}
