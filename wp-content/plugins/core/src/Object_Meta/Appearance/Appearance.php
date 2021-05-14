<?php declare( strict_types=1 );

namespace Tribe\Project\Object_Meta\Appearance;

/**
 * Shared Appearance Object Meta Configuration.
 *
 * @package Tribe\Project\Object_Meta
 */
interface Appearance {

	public const PAGE_THEME_OVERRIDE = 'page_theme_override';
	public const COLOR_THEME         = 'color_theme';
	public const COLOR_THEME_DEFAULT = '#FFF';
	public const OPTION_LIGHT        = '#FFF';
	public const OPTION_DARK         = '#000';
	public const BODY_LIGHT_CLASS    = 't-theme--light';
	public const BODY_DARK_CLASS     = 't-theme--dark';
}
