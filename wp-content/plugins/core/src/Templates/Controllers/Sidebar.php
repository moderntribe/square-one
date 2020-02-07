<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Component_Factory;
use Twig\Environment;

class Sidebar extends Abstract_Template {
	protected $sidebar_id;

	public function __construct( string $path, Environment $twig, Component_Factory $factory, string $sidebar_id = 'main' ) {
		parent::__construct( $path, $twig, $factory );
		$this->sidebar_id = $sidebar_id;
	}


	public function get_data(): array {
		$sidebar = [];

		$sidebar['active']  = is_active_sidebar( $this->sidebar_id );
		$sidebar['content'] = $sidebar['active'] ? $this->get_dynamic_sidebar() : '';

		return $sidebar;
	}

	public function get_dynamic_sidebar() {
		ob_start();
		dynamic_sidebar( $this->sidebar_id );

		return ob_get_clean();
	}
}
