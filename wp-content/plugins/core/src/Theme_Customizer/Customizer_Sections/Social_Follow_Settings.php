<?php declare(strict_types=1);

namespace Tribe\Project\Theme_Customizer\Customizer_Sections;

class Social_Follow_Settings extends Abstract_Setting {

	public const SOCIAL_SECTION = 'social_section';

	public const SOCIAL_FACEBOOK  = 'facebook';
	public const SOCIAL_INSTAGRAM = 'instagram';
	public const SOCIAL_LINKEDIN  = 'linkedin';
	public const SOCIAL_PINTEREST = 'pinterest';
	public const SOCIAL_TWITTER   = 'twitter';
	public const SOCIAL_YOUTUBE   = 'youtube';

	public function section_title(): self {
		$this->wp_customize->add_section( self::SOCIAL_SECTION, [
			'title'    => __( 'Social Links', 'tribe' ),
			'priority' => 180,
		] );

		return $this;
	}

	public function field_all_links(): self {
		foreach ( $this->social_array() as $key => $label ) {
			$this->wp_customize->add_setting( $key, [
				'sanitize_callback' => 'esc_url',
			] );
			$this->wp_customize->add_control( new \WP_Customize_Control(
				$this->wp_customize,
				$key,
				[
					'label'    => $label,
					'section'  => self::SOCIAL_SECTION,
					'settings' => $key,
					'type'     => 'url',
				]
			) );
		}

		return $this;
	}

	/**
	 * @return array
	 */
	private function social_array(): array {
		return [
			self::SOCIAL_FACEBOOK  => __( 'Facebook Link', 'tribe' ),
			self::SOCIAL_INSTAGRAM => __( 'Instagram Link', 'tribe' ),
			self::SOCIAL_LINKEDIN  => __( 'Linkedin Link', 'tribe' ),
			self::SOCIAL_PINTEREST => __( 'Pinterest Link', 'tribe' ),
			self::SOCIAL_TWITTER   => __( 'Twitter Link', 'tribe' ),
			self::SOCIAL_YOUTUBE   => __( 'Youtube Link', 'tribe' ),
		];
	}

}
