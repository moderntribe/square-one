<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Global_Fields\Color_Theme;

use Tribe\Project\Blocks\Global_Fields\Block_Model;
use Tribe\Project\Object_Meta\Appearance\Appearance;

/**
 * Global color block settings.
 *
 * @package Tribe\Project\Blocks\Global_Fields\Color_Theme
 */
class Color_Theme_Model extends Block_Model {

	public const ATTRS       = 'attrs';

	protected function set_data(): array {
		return [
			self::ATTRS => $this->get_attrs(),
		];
	}

	protected function get_attrs(): array {
		return [
			'style' => sprintf( '--%s:%s;', Appearance::COLOR_THEME, $this->get_color_theme() ),
		];
	}

	protected function get_color_theme(): string {
		$has_theme = $this->get( Appearance::PAGE_THEME_OVERRIDE, false, $this->block_id );

		if ( ! $has_theme ) {
			return Appearance::COLOR_THEME_DEFAULT;
		}

		return $this->get( Appearance::COLOR_THEME, Appearance::COLOR_THEME_DEFAULT, $this->block_id );
	}
}
