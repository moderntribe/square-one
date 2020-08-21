<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\card;

use Tribe\Project\Templates\Components\Abstract_Controller;

class Card_Controller extends Abstract_Controller {
	public function __construct( array $args = [] ) {
		$args = $this->parse_args( $args );
	}

	protected function defaults(): array {
		return [];
	}

	protected function required(): array {
		return [];
	}
}
