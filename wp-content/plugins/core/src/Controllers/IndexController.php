<?php

namespace Tribe\Project\Controllers;

use Tribe\Project\Models\Menu;
use Tribe\Project\Models\Post;
use Tribe\Project\Templates\Components\Main;
use Tribe\Project\Templates\Components\Page\Index;

class IndexController extends Controller {

	public function loop() {
		$args = [
			'main'     => $this->get_main_content(),
			'masthead' => $this->get_masthead_content(),
		];

		$this->render_component( 'document/Document.php', $args );
	}

	protected function get_main_content() {
		return [
			Main::HEADER        => '',
			Main::TEMPLATE_TYPE => 'page/index/Index.php',
			Main::CONTENT       => $this->get_index_content(),
		];
	}

	protected function get_index_content() {
		$posts = [];
		while ( have_posts() ) {
			the_post();
			$posts[] = new Post();
		}
		rewind_posts();

		return [
			Index::SUBHEADER => '',
			Index::POSTS     => $posts,
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

}
