<?php

namespace Tribe\Project\Templates;

use Tribe\Project\Twig\Twig_Template;

class Component extends Twig_Template {

	protected $context;

	public function __construct( $template, \Twig_Environment $twig = null ) {
		parent::__construct( $template, $twig );
		$this->context = $this->get_context();
	}

	public function get_data(): array {
		$data = [];
		$data['context'] = $this->context;
		return $data;
	}

	/**
	 * Get the context for this component. If currently within a panel loop return that, otherwise get the current
	 * global context. Can be overwritten by each child component class to provide more specific context determination.
	 *
	 * @return string
	 */
	protected function get_context(): string {
		if ( ! empty( get_the_panel() ) && have_panels() ) {
			return 'panel';
		}

		return apply_filters( 'component_context', 'default' );
	}

}