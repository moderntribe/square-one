<?php

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF;

/**
 * Class Post
 * @package Tribe\Project\Object_Meta
 */
class General_Settings extends ACF\ACF_Meta_Group {

	const NAME = 'general_settings';

	const TAB_SOCIAL = 'tab_social';
	const FACEBOOK   = 'facebook';
	const TWITTER    = 'twitter';
	const YOUTUBE    = 'youtube';
	const LINKEDIN   = 'linkedin';
	const PINTEREST  = 'pinterest';
	const INSTAGRAM  = 'instagram';
	const GOOGLE     = 'google-plus';

	const TAB_SITE_TAGS = 'tab_site_tags';
	const ID_GTM        = 'id_google_tag_manager';

	public function get_keys() {
		return [
			static::FACEBOOK,
			static::TWITTER,
			static::YOUTUBE,
			static::LINKEDIN,
			static::PINTEREST,
			static::INSTAGRAM,
			static::GOOGLE,
			static::ID_GTM,
		];
	}

	public function get_value( $key, $post_id = 'option' ) {
		return in_array( $key, $this->get_keys(), true ) ? get_field( $key, $post_id ) : null;
	}

	public static function get_social_follow_message( $key ) {
		switch ( $key ) {
			case self::FACEBOOK:
				return __( 'Like us on Facebook', 'tribe' );
			case self::TWITTER:
				return __( 'Follow us on Twitter', 'tribe' );
			case self::YOUTUBE:
				return __( 'Follow us on YouTube', 'tribe' );
			case self::LINKEDIN:
				return __( 'Add us on LinkedIn', 'tribe' );
			case self::PINTEREST:
				return __( 'Follow us on Pinterest', 'tribe' );
			case self::INSTAGRAM:
				return __( 'Follow us on Instagram', 'tribe' );
			case self::GOOGLE:
				return __( 'Follow us on Google+', 'tribe' );
			default:
				return '';
		}
	}

	public function get_group_config() {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'General Settings', 'tribe' ) );

		$group->add_field( $this->get_social_group_tab() );
		$group->add_field( $this->get_social_field( __( 'Facebook', 'tribe' ), self::FACEBOOK ) );
		$group->add_field( $this->get_social_field( __( 'Twitter', 'tribe' ), self::TWITTER ) );
		$group->add_field( $this->get_social_field( __( 'LinkedIn', 'tribe' ), self::LINKEDIN ) );
		$group->add_field( $this->get_social_field( __( 'Pinterest', 'tribe' ), self::PINTEREST ) );
		$group->add_field( $this->get_social_field( __( 'YouTube', 'tribe' ), self::YOUTUBE ) );
		$group->add_field( $this->get_social_field( __( 'Instagram', 'tribe' ), self::INSTAGRAM ) );
		$group->add_field( $this->get_social_field( __( 'Google+', 'tribe' ), self::GOOGLE ) );

		$group->add_field( $this->get_site_tags_group_tab() );
		$group->add_field( $this->get_site_tags_gtm_field() );

		return $group->get_attributes();
	}

	private function get_social_group_tab() {
		$field = new ACF\Field( self::NAME . '_' . self::TAB_SOCIAL );
		$field->set_attributes( [
			'label'     => __( 'Social', 'tribe' ),
			'name'      => self::TAB_SOCIAL,
			'type'      => 'tab',
			'placement' => 'left',
		] );

		return $field;
	}

	private function get_social_field( $field_label, $field_id, $type = 'url' ) {
		$field = new ACF\Field( self::NAME . '_' . $field_id );
		$field->set_attributes( [
			'label' => $field_label,
			'name'  => $field_id,
			'type'  => $type,
		] );

		return $field;
	}

	private function get_site_tags_group_tab() {
		$field = new ACF\Field( self::NAME . '_' . self::TAB_SITE_TAGS );
		$field->set_attributes( [
			'label'     => __( 'Site Tags', 'tribe' ),
			'name'      => self::TAB_SITE_TAGS,
			'type'      => 'tab',
			'placement' => 'left',
		] );

		return $field;
	}

	private function get_site_tags_gtm_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ID_GTM );
		$field->set_attributes( [
			'label'       => __( 'Google Tag Manager ID', 'tribe' ),
			'name'        => self::ID_GTM,
			'type'        => 'text',
			'placeholder' => __( 'Enter Google Tag Manager ID (GTM-XXXX)', 'tribe' ),
		] );

		return $field;
	}

}
