<?php

namespace Tribe\Project\Templates;

use Tribe\Project\Twig\Twig_Template;
use Twig\Environment;

class Sidebar extends Twig_Template {

	protected $sidebar_id;

	public function __construct( $template, Environment $twig = null, $sidebar_id = 'sidebar' ) {
		$this->sidebar_id = $sidebar_id;
		parent::__construct( $template, $twig );
	}


	public function get_data(): array {
		$data['sidebar'] = $this->get_sidebar();

		return $data;
	}

	protected function get_sidebar() {
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

	public static function instance( $sidebar = 'main' ) {
		switch ( $sidebar ) {
			case 'main':
			default:
				return tribe_project()->container()['twig.templates.sidebar.main'];
		}
	}
}
