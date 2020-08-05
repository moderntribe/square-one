<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\header\subheader;

use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {

	public function title(): string {
		// TODO: hook up/use text component.
		//  Also, make sure it handles CPTs with dates.
		return get_the_title();
	}
}
