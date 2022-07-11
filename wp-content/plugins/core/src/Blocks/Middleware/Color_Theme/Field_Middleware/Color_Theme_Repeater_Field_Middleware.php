<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme\Field_Middleware;

use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Traits\With_Field_Finder;
use Tribe\Project\Block_Middleware\Contracts\Abstract_Field_Middleware;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Appearance;
use Tribe\Project\Blocks\Middleware\Color_Theme\Traits\With_Color_Choices;

/**
 * Inject a color theme field inside a parent ACF field, e.g. an ACF Repeater, Group or Section.
 */
class Color_Theme_Repeater_Field_Middleware extends Abstract_Field_Middleware implements Appearance {

	use With_Field_Finder;
	use With_Color_Choices;

	public const MIDDLEWARE_KEY = 'color_theme_parent_key';

	/**
	 * @param \Tribe\Libs\ACF\Block_Config      $block
	 * @param array{color_theme_parent_key?: string} $params
	 *
	 * @return \Tribe\Libs\ACF\Block_Config
	 */
	protected function set_fields( Block_Config $block, array $params = [] ): Block_Config {
		$parent_key = $params[ self::MIDDLEWARE_KEY ] ?? '';

		if ( ! $parent_key ) {
			return $block;
		}

		$fields       = $block->get_fields();
		$block_name   = $block->get_block()->get_attributes()['name'];
		$parent_field = $this->find_field( $fields, $parent_key );

		if ( ! $parent_field ) {
			return $block;
		}

		$parent_field->add_field( new Field( $block_name . '_' . self::COLOR_THEME, [
			'label'         => esc_html__( 'Color Theme', 'tribe' ),
			'type'          => 'swatch',
			'name'          => self::COLOR_THEME,
			'default_value' => self::THEME_DEFAULT,
			'allow_null'    => false,
			'allow_other'   => false,
			'choices'       => $this->get_color_theme_choices(),
		] ) );

		return $block->set_fields( $fields );
	}

}
