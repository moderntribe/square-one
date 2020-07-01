<?php

namespace Tribe\Project\Controllers;

use Tribe\Project\Models\Menu;
use Tribe\Project\Models\Post;
use Tribe\Project\Templates\Components\Header\Subheader;
use Tribe\Project\Templates\Components\Main;
use Tribe\Project\Templates\Components\Page\Index;
use Tribe\Project\Templates\Components\Page\Page;
use Tribe\Project\Templates\Components\Sidebar;
use Tribe\Project\Templates\Components\Text;

class Error_Controller extends Controller {

	public function error_404() {
		$args = [
			'main'     => $this->get_main_content(),
			'masthead' => $this->get_masthead_content(),
			'sidebar'  => [],
			'footer'   => $this->get_footer_content(),
		];

		$this->render_component( 'document/Document.php', $args );
	}

	protected function get_main_content() {
		return [
			Main::TEMPLATE_TYPE => 'page/404/Error_404.php',
			Main::CONTENT       => [],
		];
	}

	protected function get_masthead_content() {
		$menu = new Menu();

		return [
			'navigation' => [
				'menu' => $menu->primary(),
			],
		];
	}


	protected function get_footer_content() {
		$menu = new Menu();

		return [
			'navigation' => [
				'menu' => $menu->secondary(),
			],
		];
	}

}
