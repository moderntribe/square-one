<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Twig\Twig_Template;

class Accordion extends Twig_Template {

	const ROWS                      = 'rows';
	const CLASSES                   = 'classes';
	const ATTRS                     = 'attrs';
	const ROW_CLASSES               = 'row_classes';
	const ROW_HEADER_CLASSES        = 'row_header_classes';
	const ROW_HEADER_INNER_CLASSES  = 'row_header_inner_classes';
	const ROW_CONTENT_CLASSES       = 'row_content_classes';
	const ROW_CONTENT_INNER_CLASSES = 'row_content_inner_classes';

	protected $accordion = [];

	public function __construct( $accordion, $template, \Twig_Environment $twig = null ) {
		parent::__construct( $template, $twig );

		$this->accordion = $accordion;
	}

	public function get_data(): array {
		$data = [
			'rows'                                => $this->accordion[self::ROWS],
			'accordion_classes'                   => $this->get_accordion_classes(),
			'accordion_attrs'                     => $this->get_accordion_attrs(),
			'accordion_row_classes'               => $this->get_accordion_row_classes(),
			'accordion_row_header_classes'        => $this->get_accordion_row_header_classes(),
			'accordion_row_header_inner_classes'  => $this->get_accordion_row_header_inner_classes(),
			'accordion_row_content_classes'       => $this->get_accordion_row_content_classes(),
			'accordion_row_content_inner_classes' => $this->get_accordion_row_content_inner_classes(),
		];

		return $data;
	}

	protected function get_accordion_classes(): string {
		$classes = [ 'c-accordion' ];

		if ( ! empty( $this->accordion[ self::CLASSES ] ) ) {
			$classes = array_merge( $classes, $this->accordion[ self::CLASSES ] );
		}

		return implode( ' ', $classes );
	}

	protected function get_accordion_row_classes(): string {
		$classes = [ 'c-accordion__row' ];

		if ( ! empty( $this->accordion[ self::ROW_CLASSES ] ) ) {
			$classes = array_merge( $classes, $this->accordion[ self::ROW_CLASSES ] );
		}

		return implode( ' ', $classes );
	}

	protected function get_accordion_row_header_classes(): string {
		$classes = [ 'c-accordion__header' ];

		if ( ! empty( $this->accordion[ self::ROW_HEADER_CLASSES ] ) ) {
			$classes = array_merge( $classes, $this->accordion[ self::ROW_HEADER_CLASSES ] );
		}

		return implode( ' ', $classes );
	}

	protected function get_accordion_row_header_inner_classes(): string {
		$classes = [ 'c-accordion__header-inner' ];

		if ( ! empty( $this->accordion[ self::ROW_HEADER_INNER_CLASSES ] ) ) {
			$classes = array_merge( $classes, $this->accordion[ self::ROW_HEADER_INNER_CLASSES ] );
		}

		return implode( ' ', $classes );
	}

	protected function get_accordion_row_content_classes(): string {
		$classes = [ 'c-accordion__content' ];

		if ( ! empty( $this->accordion[ self::ROW_CONTENT_CLASSES ] ) ) {
			$classes = array_merge( $classes, $this->accordion[ self::ROW_CONTENT_CLASSES ] );
		}

		return implode( ' ', $classes );
	}

	protected function get_accordion_row_content_inner_classes(): string {
		$classes = [ 'c-accordion__content-inner' ];

		if ( ! empty( $this->accordion[ self::ROW_CONTENT_INNER_CLASSES ] ) ) {
			$classes = array_merge( $classes, $this->accordion[ self::ROW_CONTENT_INNER_CLASSES ] );
		}

		return implode( ' ', $classes );
	}

	protected function get_accordion_attrs(): string {
		$additional_attrs = '';

		if ( ! empty( $this->accordion[ self::ATTRS ] ) ) {
			$additional_attrs = ' ' . $this->accordion[ self::ATTRS ];
		}

		return sprintf( 'data-js="c-accordion"%s', $additional_attrs );
	}

	/**
	 * Get an instance of this controller bound to the correct data.
	 *
	 * @param        $accordion
	 * @param string $template
	 *
	 * @return static
	 */
	public static function factory( $accordion, $template = 'components/accordion.twig' ) {
		return new static( $accordion, $template );
	}
}