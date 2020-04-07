<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Header;

use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Header\Header_Default as Header_Context;
use Tribe\Project\Templates\Components\Search;
use Tribe\Project\Templates\Controllers\Header\Navigation as Navigation;

class Header_Default extends Abstract_Controller {
	/**
	 * @var Navigation
	 */
	private $navigation;

	public function __construct( Component_Factory $factory, Navigation $navigation ) {
		parent::__construct( $factory );
		$this->navigation = $navigation;
	}

	public function render( string $path = '' ): string {
		return $this->factory->get( Header_Context::class, [
			Header_Context::NAVIGATION => $this->navigation->render(),
			Header_Context::LOGO       => $this->get_logo(),
			Header_Context::SEARCH     => $this->get_search(),
		] )->render( $path );
	}

	protected function get_logo() {
		return sprintf(
			'<%1$s class="logo" data-js="logo"><a href="%2$s" rel="home">%3$s</a></%1$s>',
			( is_front_page() ) ? 'h1' : 'div',
			esc_url( home_url() ),
			get_bloginfo( 'blogname' )
		);
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
			Search::LABEL_TEXT    => 'Search',
			Search::INPUT_CLASSES => [ 'c-search__input' ],
			Search::INPUT_ATTRS   => $input_attrs,
			Search::SUBMIT_BUTTON => $get_submit_button,
		];

		$search = $this->factory->get( Search::class, $options );

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

		$button = $this->factory->get( Button::class, $options );

		return $button->render();
	}

}
