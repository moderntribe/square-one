<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Global_Fields\Color_Theme;

use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Traits\With_Field_Prefix;
use Tribe\Project\Blocks\Global_Fields\Block_Meta;
use Tribe\Project\Object_Meta\Appearance\Appearance;

class Color_Theme_Meta extends Block_Meta implements Appearance {

	use With_Field_Prefix;

	public const NAME = 'global_color';

	public const SECTION = 's-colors';

	protected function add_fields(): void {
		$this->add_field( new Field_Section( self::SECTION, __( 'Appearance', 'tribe' ), 'accordion' ) )
			 ->add_field( $this->get_page_theme_override_field() )
			 ->add_field( $this->get_color_theme_field() );
	}

	protected function get_page_theme_override_field(): Field {
		return new Field( self::NAME . '_' . self::PAGE_THEME_OVERRIDE, [
			'type'   => 'radio',
			'name'   => self::PAGE_THEME_OVERRIDE,
			'label'  => __( 'Color Theme', 'tribe' ),
			'layout' => 'horizontal',
			'choices' => [
				'page'   => 'Page',
				'custom' => 'Custom',
			],
		] );
	}

	protected function get_color_theme_field(): Field {
		return new Field( self::NAME . '_' . self::COLOR_THEME, [
			'type'              => 'swatch',
			'name'              => self::COLOR_THEME,
			'label'             => __( 'Custom Color Theme', 'tribe' ),
			'choices'           => [
				self::OPTION_LIGHT => __( 'Light', 'tribe' ),
				self::OPTION_DARK  => __( 'Dark', 'tribe' ),
			],
			'allow_null'        => false,
			'allow_other'       => false,
			'conditional_logic' => [
				[
					[
						'field'    => $this->get_key_with_prefix( self::PAGE_THEME_OVERRIDE ),
						'operator' => '==',
						'value'    => 'custom',
					],
				],
			],
		] );
	}

}
