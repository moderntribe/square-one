<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme\Contracts;

use Tribe\Project\Theme\Theme_Definer;

/**
 * The color theme field's default configuration.
 *
 * @see Theme_Definer
 */
interface Appearance {

	public const COLOR_THEME = 'color_theme';

	public const THEME_DEFAULT = Theme_Definer::WHITE;

}
