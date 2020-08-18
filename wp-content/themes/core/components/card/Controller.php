<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\card;

use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {
	public function __construct( array $args = [] ) {
		$args = wp_parse_args( $args, $this->defaults() );

		foreach ( $this->required() as $key => $value ) {
			$args[$key] = array_merge( $args[$key], $value );
		}
	}

	protected function defaults(): array {
		return [];
	}

	protected function required(): array {
		return [];
	}
}
