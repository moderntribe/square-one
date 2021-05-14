<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta\Appearance;

use Tribe\Libs\ACF;
use Tribe\Libs\ACF\Field;

class Post_Appearance_Settings extends ACF\ACF_Meta_Group implements Appearance {

	use ACF\Traits\With_Field_Prefix;

	public const NAME = 'post_appearance';

	public function get_keys(): array {
		return [
			self::COLOR_THEME,
		];
	}

	protected function get_group_config(): array {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Appearance', 'tribe' ) );

		$group->add_field( $this->get_page_theme_override_field() );
		$group->add_field( $this->get_color_theme_field() );

		return $group->get_attributes();
	}

	private function get_page_theme_override_field(): Field {
		return new Field( self::NAME . '_' . self::PAGE_THEME_OVERRIDE, [
			'type'  => 'true_false',
			'name'  => Appearance::PAGE_THEME_OVERRIDE,
			'label' => __( 'Override Global Appearance Theme', 'tribe' ),
			'ui'    => true,
		] );
	}

	private function get_color_theme_field(): ACF\Field {
		$field = new ACF\Field( self::NAME . '_' . self::COLOR_THEME );

		$field->set_attributes( [
			'label'             => __( 'Color Theme', 'tribe' ),
			'name'              => 'color_theme',
			'type'              => 'swatch',
			'choices'           => [
				self::OPTION_LIGHT => __( 'Light', 'tribe' ),
				self::OPTION_DARK  => __( 'Dark', 'tribe' ),
			],
			'allow_null'        => false,
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

		return $field;
	}
}
