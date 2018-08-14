<?php

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF;

/**
 * Class Post
 * @package Tribe\Project\Object_Meta
 */
class Site_Tags_Settings extends ACF\ACF_Meta_Group {

	const NAME = 'site_tags_settings';

	const SITE_TAG_GTM = 'id_google_tag_manager';

	public function get_keys() {
		return [
			static::SITE_TAG_GTM,
		];
	}

	public function get_value( $key, $post_id = 'option' ) {
		return in_array( $key, $this->get_keys(), true ) ? get_field( $key, $post_id ) : null;
	}

	public function get_group_config() {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Site Tags Settings', 'tribe' ) );

		$group->add_field( $this->get_site_tag_gtm_field() );

		return $group->get_attributes();
	}

	private function get_site_tag_gtm_field() {
		$field = new ACF\Field( self::NAME . '_' . self::SITE_TAG_GTM );
		$field->set_attributes( [
			'label'       => __( 'Google Tag Manager ID', 'tribe' ),
			'name'        => self::SITE_TAG_GTM,
			'type'        => 'text',
			'placeholder' => __( 'Enter Google Tag Manager ID (GTM-XXXX)', 'tribe' ),
		] );

		return $field;
	}

}
