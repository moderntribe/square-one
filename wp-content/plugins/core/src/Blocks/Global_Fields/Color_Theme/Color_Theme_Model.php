<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Global_Fields\Color_Theme;

use Tribe\Project\Blocks\Global_Fields\Block_Model;
use Tribe\Project\Object_Meta\Appearance\Appearance;
use Tribe\Project\Theme\Appearance\Appearance_Class_Manager;

/**
 * Global color block settings.
 *
 * @package Tribe\Project\Blocks\Global_Fields\Color_Theme
 */
class Color_Theme_Model extends Block_Model {

	public const CLASSES = 'classes';

	protected Appearance_Class_Manager $class_manager;

	public function __construct( Appearance_Class_Manager $class_manager ) {
		$this->class_manager = $class_manager;
	}

	protected function set_data(): array {
		return [
			self::CLASSES => $this->get_classes(),
		];
	}

	protected function get_classes(): array {
		$hex = $this->get_color_theme();

		// Light or Dark CSS classes
		return [
			$this->class_manager->get_class_from_hex( $hex ),
		];
	}

	protected function get_color_theme(): string {
		$theme = $this->get( Appearance::PAGE_THEME_OVERRIDE, Appearance::OPTION_PAGE, $this->block_id );

		if ( empty( $theme ) || Appearance::OPTION_PAGE === (string) $theme ) {
			return Appearance::COLOR_THEME_DEFAULT;
		}

		return $this->get( Appearance::COLOR_THEME, Appearance::COLOR_THEME_DEFAULT, $this->block_id );
	}
}
