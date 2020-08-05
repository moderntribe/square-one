<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\document\header;

use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {

	public function language_attributes(): string {
		return get_language_attributes();
	}

	public function body_class(): string {
		return implode( ' ', get_body_class() );
	}
}
