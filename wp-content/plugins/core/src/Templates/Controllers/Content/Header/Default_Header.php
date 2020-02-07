<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Content\Header;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Button;
use Tribe\Project\Templates\Components\Search;
use Tribe\Project\Templates\Controllers\Content\Navigation\Header as Navigation;
use Tribe\Project\Theme\Logo;
use Twig\Environment;

class Default_Header extends Abstract_Template {
	/**
	 * @var Navigation
	 */
	private $navigation;

	public function __construct( string $path, Environment $twig, Component_Factory $factory, Navigation $navigation ) {
		parent::__construct( $path, $twig, $factory );
		$this->navigation = $navigation;
	}

	public function get_data(): array {
		return [
			'navigation' => $this->navigation->render(),
			'logo'       => $this->get_logo(),
			'search'     => $this->get_search(),
		];
	}

	protected function get_logo() {
		$args = [
			'echo' => false,
		];

		return Logo::logo( $args );
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

}
