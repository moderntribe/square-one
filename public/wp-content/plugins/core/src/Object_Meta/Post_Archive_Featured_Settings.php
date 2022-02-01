<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF;

class Post_Archive_Featured_Settings extends ACF\ACF_Meta_Group {

	public const NAME = 'post_archive_featured_settings';

	public const FEATURED_POSTS = 'featured_posts';

	public function get_keys(): array {
		return [
			self::FEATURED_POSTS,
		];
	}

	public function get_group_config(): array {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Featured Posts', 'tribe' ) );
		$group->add_field(
			new ACF\Field( self::NAME . '_' . self::FEATURED_POSTS, [
				'label' => esc_html__( 'Featured Posts', 'tribe' ),
				'name'  => self::FEATURED_POSTS,
				'type'  => 'relationship',
				'min'   => 0,
				'max'   => 7,
			])
		);

		return $group->get_attributes();
	}

}
