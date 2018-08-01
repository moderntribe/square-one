<?php


namespace Tribe\Project\Templates;

use Tribe\Project\Service_Providers\Nav_Menu_Provider;
use Tribe\Project\Theme\Logo;
use Tribe\Project\Templates\Components\Search;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Twig\Stringable_Callable;
use Tribe\Project\Twig\Template_Interface;
use Tribe\Project\Twig\Twig_Template;
use Tribe\Project\Object_Meta\Social_Settings;

class Base extends Twig_Template {

	public function get_data(): array {
		$data = [
			'page_title'          => $this->get_page_title(),
			'body_class'          => $this->get_body_class(),
			'logo'                => $this->get_logo(),
			'menu'                => $this->get_nav_menus(),
			'search_url'          => $this->get_search_url(),
			'home_url'            => $this->get_home_url(),
			'copyright'           => $this->get_copyright(),
			'language_attributes' => $this->get_language_attributes(),
			'search'              => $this->get_search(),
			'social_follow'       => $this->get_social_follow(),
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
			Nav_Menu_Provider::PRIMARY   => new Stringable_Callable( [ $this, 'get_primary_nav_menu' ] ),
			Nav_Menu_Provider::SECONDARY => new Stringable_Callable( [ $this, 'get_secondary_nav_menu' ] ),
		];
	}

	public function get_primary_nav_menu() {
		$args = [
			'theme_location'  => Nav_Menu_Provider::PRIMARY,
			'container'       => false,
			'container_class' => '',
			'menu_class'      => '',
			'menu_id'         => '',
			'depth'           => 3,
			'items_wrap'      => '%3$s',
			'fallback_cb'     => false,
			'echo'            => false,
			'walker'          => new \Tribe\Project\Theme\Nav\Walker_Nav_Menu_Primary(),
		];

		return \Tribe\Project\Theme\Nav\Menu::menu( $args );
	}

	public function get_secondary_nav_menu() {
		$args = [
			'theme_location'  => Nav_Menu_Provider::SECONDARY,
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

	protected function get_search(): string {
		$get_submit_button = $this->submit_button();

		$form_attrs = [
			'role'   => 'search',
			'method' => 'get',
			'action' => esc_url( get_home_url() ),
		];

		$label_attrs = [
			'for' => 's',
		];

		$input_attrs = [
			'type' => 'text',
			'id'   => 's',
			'name' => 's',
		];

		$options = [
			Search::FORM_CLASSES  => [ 'c-search' ],
			Search::FORM_ATTRS    => $form_attrs,
			Search::LABEL_CLASSES => [ 'c-search__label' ],
			Search::LABEL_ATTRS   => $label_attrs,
			Search::LABEL_TEXT    => [ 'Search' ],
			Search::INPUT_CLASSES => [ 'c-search__input' ],
			Search::INPUT_ATTRS   => $input_attrs,
			Search::SUBMIT_BUTTON => $get_submit_button,
		];

		$search = Search::factory( $options );

		return $search->render();
	}

	protected function submit_button(): string {

		$btn_attr = [
			'type'  => 'submit',
			'name'  => 'submit',
			'value' => __( 'Search', 'tribe' ),
		];

		$options = [
			Button::LABEL   => __( 'Search', 'tribe' ),
			Button::CLASSES => [ 'c-button' ],
			Button::ATTRS   => $btn_attr,
		];

		$button = Button::factory( $options );

		return $button->render();
	}

	/**
	 * @return array
	 */
	protected function get_social_follow(): array {
		$links = [];

		// Change the order of this array to change the display order
		$social_keys = [
			Social_Settings::FACEBOOK,
			Social_Settings::TWITTER,
			Social_Settings::YOUTUBE,
			Social_Settings::LINKEDIN,
			Social_Settings::PINTEREST,
			Social_Settings::INSTAGRAM,
			Social_Settings::GOOGLE,
		];

		foreach ( $social_keys as $social_site ) {
			$social_link = get_field( $social_site, 'option' );

			if ( ! empty( $social_link ) ) {
				$links[ $social_site ] = [
					'url'   => $social_link,
					'title' => Social_Settings::get_social_follow_message( $social_site ),
				];
			}
		}

		return $links;
	}
}