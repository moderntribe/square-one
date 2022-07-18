<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF;

class Theme_Options extends ACF\ACF_Meta_Group {

	public const NAME = 'theme_options';

	 // Analytics
	public const ANALYTICS_TAB    = 'tab_analytics';
	public const ANALYTICS_GTM_ID = 'analytics_gtm_id';

	 // Social Media
	public const SOCIAL_TAB       = 'tab_social';
	public const SOCIAL_FACEBOOK  = 'social_facebook';
	public const SOCIAL_TWITTER   = 'social_twitter';
	public const SOCIAL_YOUTUBE   = 'social_youtube';
	public const SOCIAL_LINKEDIN  = 'social_linkedin';
	public const SOCIAL_PINTEREST = 'social_pinterest';
	public const SOCIAL_INSTAGRAM = 'social_instagram';

	public function get_keys(): array {
		return [
		   static::ANALYTICS_GTM_ID,
		   static::SOCIAL_FACEBOOK,
		   static::SOCIAL_TWITTER,
		   static::SOCIAL_YOUTUBE,
		   static::SOCIAL_LINKEDIN,
		   static::SOCIAL_PINTEREST,
		   static::SOCIAL_INSTAGRAM,
		];
	}

	public function get_group_config(): array {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Theme Options', 'tribe' ) );

		// Analytics Tab
		$group->add_field( $this->get_options_tab_field( __( 'Analytics', 'tribe' ), self::ANALYTICS_TAB ) );
		$group->add_field( $this->get_analytics_gtm_id_field() );

		// Social Media Tab
		$group->add_field( $this->get_options_tab_field( __( 'Social Media', 'tribe' ), self::SOCIAL_TAB ) );
		$group->add_field( $this->get_social_field( __( 'Facebook', 'tribe' ), self::SOCIAL_FACEBOOK ) );
		$group->add_field( $this->get_social_field( __( 'Twitter', 'tribe' ), self::SOCIAL_TWITTER ) );
		$group->add_field( $this->get_social_field( __( 'LinkedIn', 'tribe' ), self::SOCIAL_LINKEDIN ) );
		$group->add_field( $this->get_social_field( __( 'Pinterest', 'tribe' ), self::SOCIAL_PINTEREST ) );
		$group->add_field( $this->get_social_field( __( 'YouTube', 'tribe' ), self::SOCIAL_YOUTUBE ) );
		$group->add_field( $this->get_social_field( __( 'Instagram', 'tribe' ), self::SOCIAL_INSTAGRAM ) );

		return $group->get_attributes();
	}

	private function get_options_tab_field( string $tab_label, string $tab_id ): ACF\Field {
		$tab_field = new ACF\Field( self::NAME . '_' . $tab_id );
		$tab_field->set_attributes( [
			'label' => $tab_label,
			'name'  => $tab_id,
			'type'  => 'tab',
		] );

		return $tab_field;
	}

	private function get_analytics_gtm_id_field(): ACF\Field {
		$field = new ACF\Field( self::NAME . '_' . self::ANALYTICS_GTM_ID );
		$field->set_attributes( [
			'label'       => __( 'Google Tag Manager ID', 'tribe' ),
			'name'        => self::ANALYTICS_GTM_ID,
			'type'        => 'text',
			'placeholder' => __( 'Enter Google Tag Manager ID (GTM-XXXX)', 'tribe' ),
		] );

		return $field;
	}

	private function get_social_field( string $field_label, string $field_id, string $type = 'url' ): ACF\Field {
		$field = new ACF\Field( self::NAME . '_' . $field_id );
		$field->set_attributes( [
			'label' => $field_label,
			'name'  => $field_id,
			'type'  => $type,
		] );

		return $field;
	}

}
