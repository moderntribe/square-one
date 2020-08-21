<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\routes\unsupported_browser;

use Tribe\Project\Assets\Theme\Theme_Build_Parser;
use Tribe\Project\Templates\Components\Abstract_Controller;

class Unsupported_Browser_Controller extends Abstract_Controller {

	/**
	 * @var Theme_Build_Parser
	 */
	private $build_parser;

	public function __construct( Theme_Build_Parser $build_parser ) {
		$this->build_parser = $build_parser;
	}

	public function get_styles(): string {
		$legacy_css = $this->build_parser->get_legacy_style_handles();
		ob_start();
		$GLOBALS['wp_styles']->do_items( $legacy_css );

		return ob_get_clean();
	}

	public function get_legacy_image_url( $filename ): string {
		if ( empty( $filename ) ) {
			return '';
		}

		return esc_url( trailingslashit( get_template_directory_uri() ) . 'assets/img/theme/legacy-browser/' . $filename );
	}

}
