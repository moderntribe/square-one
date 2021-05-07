<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Global_Field_Meta;

use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Traits\With_Field_Prefix;

class Color_Meta extends Block_Meta {

	use With_Field_Prefix;

	public const NAME = 'global_color';

	public const SECTION             = 's-colors';
	public const PAGE_THEME_OVERRIDE = 'page_theme_override';
	public const COLOR_THEME         = 'color_theme';
	public const COLOR_THEME_DEFAULT = '#FFF'; // transparent.
	public const COLOR_THEME_CHOICES = [
		'#FFF' => 'Light',
		'#000' => 'Dark',
	];

	protected function add_fields(): void {
		$this->add_field( new Field_Section( self::SECTION, __( 'Color Settings', 'tribe' ), 'accordion' ) )
			  ->add_field( $this->get_page_theme_override_field() )
			  ->add_field( $this->get_color_theme_field() );
	}

	protected function get_page_theme_override_field(): Field {
		return new Field( self::NAME . '_' . self::PAGE_THEME_OVERRIDE, [
			'type'  => 'true_false',
			'name'  => self::PAGE_THEME_OVERRIDE,
			'label' => __( 'Override Page Theme', 'tribe' ),
			'ui'    => true,
		] );
	}

	protected function get_color_theme_field(): Field {
		return new Field( self::NAME . '_' . self::COLOR_THEME, [
			'type'              => 'swatch',
			'name'              => self::COLOR_THEME,
			'label'             => __( 'Color Theme', 'tribe' ),
			'choices'           => self::COLOR_THEME_CHOICES,
			'allow_null'        => false,
			'allow_other'       => true,
			'default_value'     => self::COLOR_THEME_DEFAULT,
			'conditional_logic' => [
				[
					[
						'field'    => $this->get_key_with_prefix( self::PAGE_THEME_OVERRIDE ),
						'operator' => '==',
						'value'    => '1',
					],
				],
			],
		] );
	}

}
