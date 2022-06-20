<?php declare(strict_types=1);

namespace Tribe\Project\Theme_Customizer\Customizer_Sections;

abstract class Abstract_Setting {

	protected \WP_Customize_Manager $wp_customize;

	public function __construct( \WP_Customize_Manager $wp_customizer ) {
		$this->wp_customize = $wp_customizer;
	}

}
