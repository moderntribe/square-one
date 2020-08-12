<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\document\head;

use Tribe\Project\Templates\Components\Abstract_Controller;
use Tribe\Project\Templates\Components\Traits\Page_Title;

class Controller extends Abstract_Controller {
	use Page_Title;

	public function name(): string {
		return get_bloginfo( 'name' );
	}

	public function pingback_url(): string {
		return get_bloginfo( 'pingback_url' );
	}
}
