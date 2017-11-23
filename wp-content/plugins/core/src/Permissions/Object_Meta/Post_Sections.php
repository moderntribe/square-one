<?php


namespace Tribe\Project\Permissions\Object_Meta;


use Tribe\Libs\ACF;
use Tribe\Project\Permissions\Taxonomies\Section\Section;

class Post_Sections extends ACF\ACF_Meta_Group {

	const NAME = 'post_sections';

	const SECTIONS = 'post_sections';

	public function get_keys() {
		return [
			self::SECTIONS,
		];
	}

	public function get_group_config() {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Sections', 'tribe' ) );
		$group->set( 'position', 'side' );

		$group->add_field( $this->get_section_field() );

		return $group->get_attributes();
	}

	private function get_section_field() {
		$field = new ACF\Field( self::NAME . '_' . self::SECTIONS );
		$field->set_attributes( [
			'label'      => __( 'Sections', 'tribe' ),
			'name'       => self::SECTIONS,
			'type'       => 'taxonomy',
			'taxonomy'   => Section::NAME,
			'multiple'   => true,
			'field_type' => 'multi_select',
			'add_term'   => false,
			'allow_null' => true,
			'load_terms' => true,
			'save_terms' => true,
		] );
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			$field->set( 'disabled', 'true' );
		}

		return $field;
	}

}