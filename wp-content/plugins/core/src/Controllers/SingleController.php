<?php

namespace Tribe\Project\Controllers;

use Tribe\Project\Models\Menu;
use Tribe\Project\Models\Post;
use Tribe\Project\Templates\Components\Header\Subheader;
use Tribe\Project\Templates\Components\Main;
use Tribe\Project\Templates\Components\Page\Page;
use Tribe\Project\Templates\Components\Sidebar;
use Tribe\Project\Templates\Components\Text;

class SingleController extends Controller {

	public function single() {
		$args = [
			'main'     => $this->get_main_content(),
			'masthead' => $this->get_masthead_content(),
			'sidebar'  => $this->get_sidebar_content(),
			'footer'   => $this->get_footer_content(),
		];

		$this->render_component( 'document/Document.php', $args );
	}

	public function unsupported() {
		$this->render_component( 'page/page-unsupported-browser/Page_Unsupported_Browser.php', [] );
	}

	public function example_app() {
		$args = $this->get_static_page_args( 'page/page-example-app/Page_Example_App.php' );


		$this->render_component( 'document/Document.php', $args );
	}

	public function grid() {
		$args = $this->get_static_page_args( 'page/page-grid/Page_Grid.php' );

		$this->render_component( 'document/Document.php', $args );
	}

	public function kitchen_sink() {
		$args = $this->get_static_page_args( 'page/page-kitchen-sink/Page_Kitchen_Sink.php' );

		$this->render_component( 'document/Document.php', $args );
	}

	public function kitchen_sink_forms() {
		$args = $this->get_static_page_args( 'page/page-kitchen-sink-forms/Page_Kitchen_Sink_Forms.php' );

		$this->render_component( 'document/Document.php', $args );
	}

	public function section() {
		$args = $this->get_static_page_args( 'page/page-section/Page_Section.php' );

		$this->render_component( 'document/Document.php', $args );
	}

	protected function get_main_content() {
		return [
			Main::TEMPLATE_TYPE => is_page() ? 'page/Page.php' : 'page/single/Single.php',
			Main::CONTENT       => $this->get_single_content(),
		];
	}

	protected function get_static_page_args( $template ) {
		return [
			'main'     => [
				Main::TEMPLATE_TYPE => $template,
				Main::CONTENT       => $this->get_static_page_main_content(),
			],
			'masthead' => $this->get_masthead_content(),
			'sidebar'  => $this->get_sidebar_content(),
			'footer'   => $this->get_footer_content(),
		];
	}

	protected function get_static_page_main_content() {
		return [
			Page::SUBHEADER => [
				Subheader::TITLE => [
					Text::TEXT => get_the_title(),
					Text::TAG  => 'h1',
				],
			],
			Page::POST      => false,
		];
	}


	protected function get_single_content() {
		return [
			Page::SUBHEADER => [
				Subheader::TITLE => [
					Text::TEXT => get_the_title(),
					Text::TAG  => 'h1',
				],
			],
			Page::POST      => new Post(),
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
		if ( is_page() ) {
			return [];
		}

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
