<?php

namespace Tribe\Project\Controllers;

use Tribe\Project\Models\Menu;
use Tribe\Project\Models\Post;
use Tribe\Project\Templates\Components\Header\Subheader;
use Tribe\Project\Templates\Components\Main;
use Tribe\Project\Templates\Components\Page\Index;
use Tribe\Project\Templates\Components\Sidebar;
use Tribe\Project\Templates\Components\Text;

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

	public function search() {
		$args = [
			'main'     => $this->get_main_search_content(),
			'masthead' => $this->get_masthead_content(),
			'sidebar'  => $this->get_sidebar_content(),
			'footer'   => $this->get_footer_content(),
		];

		$this->render_component( 'document/Document.php', $args );
	}

	protected function get_main_content() {
		return [
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
			Index::SUBHEADER => [],
			Index::POSTS     => $posts,
		];
	}

	protected function get_main_search_content() {
		return [
			Main::TEMPLATE_TYPE => 'page/search/Search.php',
			Main::CONTENT       => $this->get_search_content(),
		];
	}

	protected function get_search_content() {
		global $wp_query;
		$posts = [];
		while ( have_posts() ) {
			the_post();
			$posts[] = new Post();
		}
		rewind_posts();

		return [
			Index::SUBHEADER => [
				Subheader::TITLE => [
					Text::TEXT => sprintf( '%s: %s', __( 'Search Results for', 'tribe' ), $wp_query->get( 's' ) ),
					Text::TAG  => 'h1',
				],
			],
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
