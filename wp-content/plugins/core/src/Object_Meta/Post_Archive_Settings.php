<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF;

class Post_Archive_Settings extends ACF\ACF_Meta_Group {

	public const NAME = 'post_archive_settings';

	public const TITLE       = 'title';
	public const DESCRIPTION = 'description';
	public const HERO_IMAGE  = 'hero_image';

	public function get_keys(): array {
		return [
			self::TITLE,
			self::DESCRIPTION,
			self::HERO_IMAGE,
		];
	}

	public function get_group_config(): array {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Post Archive Settings', 'tribe' ) );
		$group->add_field( $this->get_field( __( 'Archive Title', 'tribe' ), self::TITLE, 'text' ) );
		$group->add_field( $this->get_field( __( 'Description', 'tribe' ), self::DESCRIPTION, 'textarea' ) );
		$group->add_field( $this->get_field( __( 'Hero Image', 'tribe' ), self::HERO_IMAGE, 'image' ) );

		return $group->get_attributes();
	}

	private function get_field( string $label, string $name, string $type ): ACF\Field {
		return new ACF\Field( self::NAME . '_' . $name, [
			'label' => $label,
			'name'  => $name,
			'type'  => $type,
		] );
	}

}
