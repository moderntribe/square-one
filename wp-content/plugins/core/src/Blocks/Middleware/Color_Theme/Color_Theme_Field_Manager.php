<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme;

use Tribe\Libs\ACF\Field;
use Tribe\Libs\Field_Models\Collections\Swatch_Collection;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Appearance;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Color_Theme_Field;

/**
 * @see Color_Theme_Field
 */
class Color_Theme_Field_Manager implements Color_Theme_Field, Appearance {

	protected Swatch_Collection $swatch_collection;

	public function __construct( Swatch_Collection $swatch_collection ) {
		$this->swatch_collection = $swatch_collection;
	}

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
			'choices'       => $this->swatch_collection->format_for_acf(),
		] );
	}

}
