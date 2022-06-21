<?php declare(strict_types=1);

namespace Tribe\Project\Theme_Customizer\Customizer_Sections;

/**
 * Class Footer.
 */
class Footer_Settings extends Abstract_Setting {

	// Keys.
	public const FOOTER_SECTION     = 'footer_section';
	public const FOOTER_LOGO        = 'footer_logo';
	public const FOOTER_DESCRIPTION = 'footer_description';

	public const SITE_DESCRIPTION_ALLOWED_HTML = [
		'a'      => [
			'href' => [],
		],
		'em'     => [],
		'strong' => [],
	];

	/**
	 * Fluent title.
	 *
	 * @return $this
	 */
	public function section_title(): self {
		$this->wp_customize->add_section( self::FOOTER_SECTION, [
			'title'    => __( 'Footer', 'tribe' ),
			'priority' => 170,
		] );

		return $this;
	}

	public function footer_logo(): self {
		$this->wp_customize->add_setting( self::FOOTER_LOGO );
		$this->wp_customize->add_control( new \WP_Customize_Media_Control(
			$this->wp_customize,
			self::FOOTER_LOGO,
			[
				'label'       => __( 'Footer Logo', 'tribe' ),
				'description' => __( 'Recommended minimum width: 700px. Recommended file type: .svg.', 'tribe' ),
				'section'     => self::FOOTER_SECTION,
				'settings'    => self::FOOTER_LOGO,
				'mime_type'   => 'image',
			]
		) );

		return $this;
	}

	public function footer_description(): self {
		$this->wp_customize->add_setting( self::FOOTER_DESCRIPTION, [
			'sanitize_callback' => [ $this, 'sanitize_site_description' ],
		] );

		$this->wp_customize->add_control( new \WP_Customize_Control(
			$this->wp_customize,
			self::FOOTER_DESCRIPTION,
			[
				'label'       => __( 'Site Description', 'tribe' ),
				'section'     => self::FOOTER_SECTION,
				'settings'    => self::FOOTER_DESCRIPTION,
				'type'        => 'textarea',
				'description' => __( 'HTML allowed: Links <code>&lt;a href=&quot;&quot;&gt;</code>, bold <code>&lt;strong&gt;</code>, and italics <code>&lt;em&gt;</code>', 'tribe' ),
			]
		) );

		return $this;
	}

	public function sanitize_site_description( string $description ): string {
		return wp_kses( $description, self::SITE_DESCRIPTION_ALLOWED_HTML );
	}

}
