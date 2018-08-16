<?php

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF;

class Analytics_Settings extends ACF\ACF_Meta_Group {

	const NAME = 'analytics_settings';

	const GOOGLE_TAG_MANAGER = 'id_google_tag_manager';

	public function get_keys() {
		return [
			static::GOOGLE_TAG_MANAGER,
		];
	}

	public function get_value( $key, $post_id = 'option' ) {
		return in_array( $key, $this->get_keys(), true ) ? get_field( $key, $post_id ) : null;
	}

	public function get_group_config() {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Site Analytics Settings', 'tribe' ) );

		$group->add_field( $this->get_site_tag_gtm_field() );

		return $group->get_attributes();
	}

	private function get_site_tag_gtm_field() {
		$field = new ACF\Field( self::NAME . '_' . self::GOOGLE_TAG_MANAGER );
		$field->set_attributes( [
			'label'       => __( 'Google Tag Manager ID', 'tribe' ),
			'name'        => self::GOOGLE_TAG_MANAGER,
			'type'        => 'text',
			'placeholder' => __( 'Enter Google Tag Manager ID (GTM-XXXX)', 'tribe' ),
		] );

		return $field;
	}

}
