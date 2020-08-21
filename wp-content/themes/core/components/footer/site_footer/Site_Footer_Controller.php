<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\footer\site_footer;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Traits\Copyright;

class Site_Footer_Controller extends Abstract_Controller {
	use Copyright;

	public function get_home_url(): string {
		return esc_url( home_url( '/' ) );
	}

	public function get_site_name(): string {
		return esc_html( get_bloginfo( 'name' ) );
	}
}
