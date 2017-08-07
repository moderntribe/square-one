<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Theme\Util;
use Tribe\Project\Twig\Twig_Template;

class Title extends Twig_Template {

	protected $title   = '';
	protected $tag     = '';
	protected $classes = [];
	protected $attrs   = [];

	public function __construct( $title, $tag, $classes, $attrs, $template, \Twig_Environment $twig = null ) {
		parent::__construct( $template, $twig );

		$this->title   = $title;
		$this->tag     = $tag;
		$this->classes = $classes;
		$this->attrs   = $attrs;
	}

	public function get_data(): array {
		$data = [
			'title'   => $this->title,
			'tag'     => $this->tag,
			'classes' => $this->get_classes(),
			'attrs'   => $this->get_attrs(),
		];

		return $data;
	}

	protected function get_classes(): string {
		return implode( ' ', $this->classes );
	}

	protected function get_attrs(): string {

		if ( empty( $this->attrs ) ) {
			return '';
		}

		return Util::array_to_attributes( $this->attrs );
	}

	/**
	 * Get an instance of this controller bound to the correct data.
	 *
	 * @param        $card
	 * @param string $template
	 *
	 * @return static
	 */
	public static function factory( $title, $tag = 'h3', $classes = [], $attrs = [], $template = 'components/title.twig' ) {
		return new static( $title, $tag, $classes, $attrs, $template );
	}

}