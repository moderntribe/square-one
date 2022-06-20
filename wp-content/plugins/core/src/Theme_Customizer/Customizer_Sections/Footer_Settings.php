<?php declare(strict_types=1);

namespace Tribe\Project\Theme_Customizer\Customizer_Sections;

/**
 * Class Footer.
 */
class Footer_Settings extends Abstract_Setting {

	// Keys.
	public const FOOTER_SECTION = 'footer_section';

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

}
