<?php


namespace Tribe\Project\Templates;

use Tribe\Project\Customizer\Pre_Footer;
use Tribe\Project\Twig\Noop_Lazy_Strings;
use Tribe\Project\Twig\Stringable_Callable;
use Tribe\Project\Twig\Template_Interface;
use Tribe\Project\Twig\Twig_Template;

class Base extends Twig_Template {

	public function get_data(): array {
		$data = [
			'page_title' => $this->get_page_title(),
			'body_class' => $this->get_body_class(),
			'menu'       => $this->get_nav_menus(),
			'lang'       => $this->get_strings(),
			'search_url' => $this->get_search_url(),
			'home_url'   => $this->get_home_url(),
			'copyright'  => $this->get_copyright(),
		];

		foreach ( $this->get_components() as $component ) {
			$data = array_merge( $data, $component->get_data() );
		}
		return $data;
	}

	/**
	 * @return Template_Interface[] Template components that will add data to this template
	 */
	protected function get_components() {
		return [];
	}

	protected function get_page_title() {
		return get_page_title();
	}

	protected function get_body_class() {
		return join( ' ', get_body_class() );
	}

	protected function get_nav_menus() {
		return [
			'primary'   => new Stringable_Callable( [ $this, 'get_primary_nav_menu' ] ),
			'secondary' => new Stringable_Callable( [ $this, 'get_secondary_nav_menu' ] ),
		];
	}

	public function get_primary_nav_menu() {
		$args = [
			'theme_location'  => 'primary',
			'container'       => false,
			'container_class' => '',
			'menu_class'      => '',
			'menu_id'         => '',
			'depth'           => 3,
			'items_wrap'      => '%3$s',
			'fallback_cb'     => false,
			'echo'            => false,
			'walker'          => new \Tribe\Project\Theme\Nav\Extensible_Walker_Nav_Menu,
		];

		return \Tribe\Project\Theme\Nav\Menu::menu( $args );
	}

	public function get_secondary_nav_menu() {
		$args = [
			'theme_location'  => 'secondary',
			'container'       => false,
			'container_class' => '',
			'menu_class'      => '',
			'menu_id'         => '',
			'depth'           => 1,
			'items_wrap'      => '%3$s',
			'fallback_cb'     => false,
			'echo'            => false,
			'item_spacing'    => 'discard',
		];

		return \Tribe\Project\Theme\Nav\Menu::menu( $args );
	}

	protected function get_strings() {
		return new Noop_Lazy_Strings( 'tribe' );
	}

	protected function get_search_url() {
		return home_url( '/' );
	}

	protected function get_home_url() {
		return home_url( '/' );
	}

	protected function get_copyright() {
		return sprintf( __( '%s %d All Rights Reserved.', 'tribe' ), '&copy;', date( 'Y' ) );
	}
}