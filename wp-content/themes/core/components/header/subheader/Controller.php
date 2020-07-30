<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\header\subheader;

use Tribe\Project\Templates\Factory_Method;

class Controller {
	use Factory_Method;

	public function title(): string {
		// TODO: hook up/use text component
		return get_the_title();
	}
}
