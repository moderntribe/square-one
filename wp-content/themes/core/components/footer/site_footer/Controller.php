<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\footer\site_footer;

use Tribe\Project\Templates\Controllers\Traits\Copyright;
use Tribe\Project\Templates\Factory_Method;

class Controller {
	use Factory_Method;
	use Copyright;

	public function home_url(): string {
		return home_url( '/' );
	}

	public function name(): string {
		return get_bloginfo( 'name' );
	}
}
