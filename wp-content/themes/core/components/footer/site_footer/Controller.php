<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\footer\site_footer;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Controllers\Traits\Copyright;

class Controller extends Abstract_Controller {
	use Copyright;

	public function home_url(): string {
		return home_url( '/' );
	}

	public function name(): string {
		return get_bloginfo( 'name' );
	}
}
