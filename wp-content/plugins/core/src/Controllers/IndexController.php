<?php

namespace Tribe\Project\Controllers;

use Tribe\Project\Models\Menu;
use Tribe\Project\Models\Post;
use Tribe\Project\Templates\Components\Main;
use Tribe\Project\Templates\Components\Page\Index;
use Tribe\Project\Templates\Components\Sidebar;

class IndexController extends Controller {

	public function loop() {
		$args = [
			'main'     => $this->get_main_content(),
			'masthead' => $this->get_masthead_content(),
			'sidebar'  => $this->get_sidebar_content(),
			'footer'   => $this->get_footer_content(),
		];

		$this->render_component( 'document/Document.php', $args );
	}

	protected function get_main_content() {
		return [
			Main::TEMPLATE_TYPE => 'page/index/Index.php',
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

	protected function get_sidebar_content() {
		return [
			Sidebar::SIDEBAR_ID => 'main',
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
