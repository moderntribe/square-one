<?php

namespace Tribe\Project\Controllers;

use Tribe\Project\Models\Menu;
use Tribe\Project\Models\Post;
use Tribe\Project\Templates\Components\Header\Subheader;
use Tribe\Project\Templates\Components\Main;
use Tribe\Project\Templates\Components\Page\Index;
use Tribe\Project\Templates\Components\Page\Page;
use Tribe\Project\Templates\Components\Sidebar;

class MainController extends Controller {

	public function single() {
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
			Main::TEMPLATE_TYPE => is_page() ? 'page/Page.php' : 'page/single/Single.php',
			Main::CONTENT       => $this->get_single_content(),
		];
	}

	protected function get_single_content() {
		return [
			Page::SUBHEADER => [
				Subheader::TITLE => get_the_title(),
			],
			Page::POST     => new Post(),
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
