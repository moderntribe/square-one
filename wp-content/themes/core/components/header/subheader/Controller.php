<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\header\subheader;

use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {

	public function title(): string {
		// TODO: implement a wrapper/container component
		// TODO: use the method in head/controller.php?
		return get_the_title();
	}
}
