<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta\Appearance;

use Tribe\Libs\ACF;

class Global_Appearance_Settings extends ACF\ACF_Meta_Group implements Appearance {

	use ACF\Traits\With_Field_Prefix;

	public const NAME = 'global_appearance';

	public function get_keys(): array {
		return [
			self::COLOR_THEME,
		];
	}

	protected function get_group_config(): array {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Appearance', 'tribe' ) );

		$group->add_field( $this->get_color_theme_field() );

		return $group->get_attributes();
	}

	private function get_color_theme_field(): ACF\Field {
		$field = new ACF\Field( self::NAME . '_' . self::COLOR_THEME );

		$field->set_attributes( [
			'label'         => __( 'Default Color Theme', 'tribe' ),
			'name'          => 'color_theme',
			'type'          => 'swatch',
			'choices'       => [
				self::OPTION_LIGHT => __( 'Light', 'tribe' ),
				self::OPTION_DARK  => __( 'Dark', 'tribe' ),
			],
			'default_value' => self::COLOR_THEME_DEFAULT,
			'allow_null'    => false,
		] );

		return $field;
	}
}
