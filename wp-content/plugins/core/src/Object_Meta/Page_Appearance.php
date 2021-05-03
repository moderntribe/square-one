<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF;
use Tribe\Project\Post_Types\Page\Page;

class Page_Appearance extends ACF\ACF_Meta_Group {

	public const NAME = 'page_appearance';

	public const COLOR_THEME = 'color_theme';
	public const COLOR_THEME_CHOICES = [
		'#FFF' => 'Light',
		'#000' => 'Dark',
	];

	public function get_keys(): array {
		return [
			self::COLOR_THEME
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
				'label' => __( 'Color Theme', 'tribe' ),
				'name' => 'color_theme',
				'type' => 'swatch',
				'choices' => self::COLOR_THEME_CHOICES,
				'allow_null' => false,
			] );

		return $field;
	}
}
