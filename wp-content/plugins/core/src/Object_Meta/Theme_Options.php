<?php declare(strict_types=1);

namespace Tribe\Project\Object_Meta;

use Tribe\Libs\ACF;
use Tribe\Project\Admin\Editor\Classic_Editor_Formats;

class Theme_Options extends ACF\ACF_Meta_Group {

	public const NAME = 'theme_options';

	// Footer
	public const FOOTER_TAB         = 'tab_footer';
	public const FOOTER_LOGO        = 'footer_logo';
	public const FOOTER_DESCRIPTION = 'footer_description';
	public const FOOTER_CTA_1       = 'footer_cta_1';
	public const FOOTER_CTA_2       = 'footer_cta_2';

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
			static::FOOTER_LOGO,
			static::FOOTER_DESCRIPTION,
			static::FOOTER_CTA_1,
			static::FOOTER_CTA_2,
			static::ANALYTICS_GTM_ID,
			static::SOCIAL_FACEBOOK,
			static::SOCIAL_TWITTER,
			static::SOCIAL_YOUTUBE,
			static::SOCIAL_LINKEDIN,
			static::SOCIAL_PINTEREST,
			static::SOCIAL_INSTAGRAM,
		];
	}

	/**
	 * @param string|int $key
	 * @param string|int $post_id
	 *
	 * @return mixed|null
	 */
	public function get_value( $key, $post_id = 'option' ) {
		return in_array( $key, $this->get_keys(), true ) ? get_field( $key, $post_id ) : null;
	}

	public function get_group_config(): array {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', esc_html__( 'Theme Options', 'tribe' ) );

		// Footer Tab
		$group->add_field( $this->get_options_tab_field( esc_html__( 'Site Footer', 'tribe' ), self::FOOTER_TAB ) );
		$group->add_field( $this->get_footer_logo_field() );
		$group->add_field( $this->get_footer_description_field() );
		$group->add_field( $this->get_footer_cta_field( esc_html__( 'Call To Action 1', 'tribe' ), self::FOOTER_CTA_1 ) );
		$group->add_field( $this->get_footer_cta_field( esc_html__( 'Call To Action 2', 'tribe' ), self::FOOTER_CTA_2 ) );

		// Analytics Tab
		$group->add_field( $this->get_options_tab_field( esc_html__( 'Analytics', 'tribe' ), self::ANALYTICS_TAB ) );
		$group->add_field( $this->get_analytics_gtm_id_field() );

		// Social Media Tab
		$group->add_field( $this->get_options_tab_field( esc_html__( 'Social Media', 'tribe' ), self::SOCIAL_TAB ) );
		$group->add_field( $this->get_social_field( esc_html__( 'Facebook', 'tribe' ), self::SOCIAL_FACEBOOK ) );
		$group->add_field( $this->get_social_field( esc_html__( 'Twitter', 'tribe' ), self::SOCIAL_TWITTER ) );
		$group->add_field( $this->get_social_field( esc_html__( 'LinkedIn', 'tribe' ), self::SOCIAL_LINKEDIN ) );
		$group->add_field( $this->get_social_field( esc_html__( 'Pinterest', 'tribe' ), self::SOCIAL_PINTEREST ) );
		$group->add_field( $this->get_social_field( esc_html__( 'YouTube', 'tribe' ), self::SOCIAL_YOUTUBE ) );
		$group->add_field( $this->get_social_field( esc_html__( 'Instagram', 'tribe' ), self::SOCIAL_INSTAGRAM ) );

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

	private function get_footer_logo_field(): ACF\Field {
		$field = new ACF\Field( self::NAME . '_' . self::FOOTER_LOGO );
		$field->set_attributes( [
			'label'         => esc_html__( 'Logo', 'tribe' ),
			'name'          => self::FOOTER_LOGO,
			'type'          => 'image',
			'return_format' => 'id',
			'instructions'  => esc_html__( 'Appears at the top of the site footer. Recommended minimum width: 700px. Recommended file type: .svg.', 'tribe' ),
		] );

		return $field;
	}

	private function get_footer_description_field(): ACF\Field {
		$field = new ACF\Field( self::NAME . '_' . self::FOOTER_DESCRIPTION );
		$field->set_attributes( [
			'label'        => esc_html__( 'Description', 'tribe' ),
			'name'         => self::FOOTER_DESCRIPTION,
			'type'         => 'wysiwyg',
			'toolbar'      => Classic_Editor_Formats::MINIMAL,
			'tabs'         => 'visual',
			'media_upload' => 0,
			'instructions' => esc_html__( 'Appears below the logo in the site footer.', 'tribe' ),
		] );

		return $field;
	}

	private function get_footer_cta_field( string $field_label, string $field_id ): ACF\Field {
		$field = new ACF\Field( self::NAME . '_' . $field_id );
		$field->set_attributes( [
			'label' => $field_label,
			'name'  => $field_id,
			'type'  => 'link',
		] );

		return $field;
	}

	private function get_analytics_gtm_id_field(): ACF\Field {
		$field = new ACF\Field( self::NAME . '_' . self::ANALYTICS_GTM_ID );
		$field->set_attributes( [
			'label'       => esc_html__( 'Google Tag Manager ID', 'tribe' ),
			'name'        => self::ANALYTICS_GTM_ID,
			'type'        => 'text',
			'placeholder' => esc_attr__( 'Enter Google Tag Manager ID (GTM-XXXX)', 'tribe' ),
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
