<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF;
use Tribe\Project\Post_Types\Page\Page;

class Page_Appearance extends ACF\ACF_Meta_Group {

	public const NAME = 'page_appearance';

	public const COLOR_THEME = 'color_theme';

	public function get_keys(): array {
		return [
			static::COLOR_THEME
		];
	}

	public function get_group_config(): array {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set_post_types( [ Page::NAME ] );
		$group->set( 'title', __( 'Page Appearance', 'tribe' ) );
		$group->add_field( $this->get_color_theme_field() );

		return $group->get_attributes();
	}

	private function get_color_theme_field(): ACF\Field {

		$field = new ACF\Field( self::NAME . '_' . self::COLOR_THEME );
		$field->set_attributes( [
				'label' => 'Color Theme',
				'name' => 'color_theme',
				'type' => 'swatch',
				'choices' => [
					'#FFF' => 'Light',
					'#000' => 'Dark',
					'blue' => 'Blue',
				],
				'allow_null' => 0,
			] );

		return $field;
	}
}
