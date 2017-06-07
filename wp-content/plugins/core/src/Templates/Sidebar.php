<?php


namespace Tribe\Project\Templates;

use Tribe\Project\Twig\Stringable_Callable;

class Sidebar extends Base {

	protected $sidebar_id = 'sidebar-main';

	public function get_data(): array {
		$data = parent::get_data();

		$data['sidebar'] = $this->get_sidebar();

		return $data;
	}

	protected function get_sidebar() {
		$sidebar = [];

		$sidebar['active']          = is_active_sidebar( $this->sidebar_id );
		$sidebar['dynamic_sidebar'] = $this->get_dynamic_sidebar();

		return $sidebar;
	}

	public function get_dynamic_sidebar() {
		dynamic_sidebar( $this->sidebar_id );
	}
}