<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme;

use Tribe\Libs\ACF\Field;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Appearance;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Color_Theme_Field;
use Tribe\Project\Blocks\Middleware\Color_Theme\Traits\With_Color_Choices;

/**
 * @see Color_Theme_Field
 */
class Color_Theme_Field_Manager implements Color_Theme_Field, Appearance {

	use With_Color_Choices;

	/**
	 * Retrieve the color theme field configuration.
	 *
	 * @param string $key_prefix The field's key prefix, often the Block::NAME constant.
	 * @param string $label The already translated and escaped label for the field.
	 */
	public function get_field( string $key_prefix, string $label = '' ): Field {
		return new Field( $key_prefix . '_' . self::COLOR_THEME, [
			'type'          => 'swatch',
			'name'          => self::COLOR_THEME,
			'label'         => $label,
			'default_value' => self::THEME_DEFAULT,
			'allow_null'    => false,
			'allow_other'   => false,
			'choices'       => $this->get_color_theme_choices(),
		] );
	}

}
