<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\document\header;

use Tribe\Project\Templates\Factory_Method;

class Controller {
	use Factory_Method;

	public function language_attributes(): string {
		return get_language_attributes();
	}

	public function body_class(): string {
		return implode( ' ', get_body_class() );
	}
}
