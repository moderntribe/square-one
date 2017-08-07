<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Theme\Util;
use Tribe\Project\Twig\Twig_Template;

class Description extends Twig_Template {

	protected $description = '';
	protected $classes     = [];
	protected $attrs       = [];

	public function __construct( $description, $classes, $attrs, $template, \Twig_Environment $twig = null ) {
		parent::__construct( $template, $twig );

		$this->description = $description;
		$this->classes     = $classes;
		$this->attrs       = $attrs;
	}

	public function get_data(): array {
		$data = [
			'content' => $this->description,
			'classes' => $this->get_classes(),
			'attrs'   => $this->get_attrs(),
		];

		return $data;
	}

	protected function get_classes() {
		return implode( ' ', $this->classes );
	}

	protected function get_attrs() {

		if ( empty( $this->attrs ) ) {
			return '';
		}

		return Util::array_to_attributes( $this->attrs );
	}

	/**
	 * Get an instance of this controller bound to the correct data.
	 *
	 * @param        $description
	 * @param string $template
	 *
	 * @return static
	 */
	public static function factory( $description, $classes, $attrs, $template = 'components/description.twig' ) {
		return new static( $description, $classes, $attrs, $template );
	}
}