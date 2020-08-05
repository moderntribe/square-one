<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\sidebar;

use Tribe\Project\Templates\Components\Abstract_Controller;

class Controller extends Abstract_Controller {

	/**
	 * @var string
	 */
	private $sidebar_id;

	public function __construct( array $args = [] ) {
		$this->sidebar_id = $args['sidebar_id'] ?? '';
	}

	public function active(): bool {
		return $this->sidebar_id && is_active_sidebar( $this->sidebar_id );
	}

	public function id(): string {
		return $this->sidebar_id;
	}

}
