<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme\Field_Middleware;

use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Project\Block_Middleware\Contracts\Abstract_Field_Middleware;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Appearance;
use Tribe\Project\Blocks\Middleware\Color_Theme\Contracts\Color_Theme_Field;
use Tribe\Project\Blocks\Middleware\Color_Theme\Traits\With_Color_Choices;

class Color_Theme_Field_Middleware extends Abstract_Field_Middleware implements Appearance, Color_Theme_Field {

	use With_Color_Choices;

	public const NAME = 'global_color';

	public const SECTION = 's-colors';

	/**
	 * Add a field section/accordion with an ACF swatch field to the root of a block.
	 *
	 * @param \Tribe\Libs\ACF\Block_Config $block
	 * @param array                        $params
	 *
	 * @return \Tribe\Libs\ACF\Block_Config
	 */
	public function set_fields( Block_Config $block, array $params = [] ): Block_Config {
		return $block->add_field( new Field_Section( self::SECTION, esc_html__( 'Color Theme', 'tribe' ), 'accordion' ) )
			  ->add_field( $this->get_color_theme_field() );
	}

	public function get_color_theme_field( string $label = '' ): Field {
		return new Field( self::NAME . '_' . self::COLOR_THEME, [
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
