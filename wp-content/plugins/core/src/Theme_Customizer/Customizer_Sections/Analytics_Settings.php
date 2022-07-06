<?php declare(strict_types=1);

namespace Tribe\Project\Theme_Customizer\Customizer_Sections;

/**
 * Class Footer.
 */
class Analytics_Settings extends Abstract_Setting {

	// Keys.
	public const ANALYTICS_SECTION = 'analytics_section';
	public const ANALYTICS_GTM_ID  = 'gtm_id';

	/**
	 * Fluent title.
	 *
	 * @return $this
	 */
	public function section_title(): self {
		$this->wp_customize->add_section( self::ANALYTICS_SECTION, [
			'title'    => esc_html__( 'Analytics', 'tribe' ),
			'priority' => 185,
		] );

		return $this;
	}

	public function field_gtm_id(): self {
		$this->wp_customize->add_setting( self::ANALYTICS_GTM_ID, [
			'sanitize_callback' => 'esc_attr',
		] );
		$this->wp_customize->add_control( new \WP_Customize_Control(
			$this->wp_customize,
			self::ANALYTICS_GTM_ID,
			[
				'label'       => esc_html__( 'Google Tag Manager ID', 'tribe' ),
				'section'     => self::ANALYTICS_SECTION,
				'setting'     => self::ANALYTICS_GTM_ID,
				'type'        => 'text',
				'description' => esc_html__( 'Google Tag Manager ID (GTM-XXXX)', 'tribe' ),
			]
		) );

		return $this;
	}

}
