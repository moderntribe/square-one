<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\sidebar;

use Tribe\Project\Templates\Factory_Method;

class Controller {
	use Factory_Method;

	/**
	 * @var string
	 */
	private $sidebar_id;

	public function __construct( string $sidebar_id ) {
		$this->sidebar_id = $sidebar_id;
	}

	public function active(): bool {
		return $this->sidebar_id && is_active_sidebar( $this->sidebar_id );
	}

	public function id(): string {
		return $this->sidebar_id;
	}

	public static function factory( array $args = [] ): self {
		return new self( $args['sidebar_id'] ?? '' );
	}

}
