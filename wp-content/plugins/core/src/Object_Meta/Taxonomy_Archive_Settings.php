<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF;

class Taxonomy_Archive_Settings extends ACF\ACF_Meta_Group {
	public const NAME = 'taxonomy_archive_settings';

	public const HERO_IMAGE = 'hero_image';

	public function get_keys(): array {
		return [
			self::HERO_IMAGE,
		];
	}

	public function get_group_config(): array {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Archive Settings', 'tribe' ) );

		$group->add_field( new ACF\Field( self::NAME . '_' . self::HERO_IMAGE, [
			'label'        => __( 'Hero Image', 'tribe' ),
			'name'         => self::HERO_IMAGE,
			'type'         => 'image',
			'instructions' => 'The hero image for this taxonomy\'s archive',
		] ));

		return $group->get_attributes();
	}
}
