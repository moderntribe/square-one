<?php


namespace Tribe\Project\Templates;

use Tribe\Project\Service_Providers\Nav_Menu_Provider;
use Tribe\Project\Theme\Logo;
use Tribe\Project\Theme\Nav\Menu\Primary_Menu;
use Tribe\Project\Theme\Nav\Menu\Secondary_Menu;
use Tribe\Project\Twig\Noop_Lazy_Strings;
use Tribe\Project\Twig\Stringable_Callable;
use Tribe\Project\Twig\Template_Interface;
use Tribe\Project\Twig\Twig_Template;

class Base extends Twig_Template {

	public function get_data(): array {
		$data = [
			'page_title'          => $this->get_page_title(),
			'body_class'          => $this->get_body_class(),
			'logo'                => $this->get_logo(),
			'menu'                => $this->get_nav_menus(),
			'lang'                => $this->get_strings(),
			'search_url'          => $this->get_search_url(),
			'home_url'            => $this->get_home_url(),
			'copyright'           => $this->get_copyright(),
			'language_attributes' => $this->get_language_attributes(),
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

	protected function get_logo() {
		$args = [
			'echo' => false,
		];

		return Logo::logo( $args );
	}

	protected function get_nav_menus() {
		return [
		    Primary_Menu::NAME   => ( new Primary_Menu() )->get_menu(),
            Secondary_Menu::NAME => ( new Secondary_Menu() )->get_menu(),
		];
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

	protected function get_language_attributes() {
		ob_start();
		language_attributes();
		return ob_get_clean();
	}
}