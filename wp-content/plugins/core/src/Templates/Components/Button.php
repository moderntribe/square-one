<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Twig\Twig_Template;

class Button extends Twig_Template {

	protected $options = [];

	public function __construct( $options, $template, \Twig_Environment $twig = null ) {
		parent::__construct( $template, $twig );

		$this->options = $this->parse_args( $options );
	}

	protected function parse_args( $options ) {
		$defaults = [
			'url'         => '',
			'type'        => 'button',
			'target'      => '_self',
			'attrs'       => [],
			'label'       => false,
			'btn_as_link' => false,
		];

		return wp_parse_args( $options, $defaults );
	}

	public function get_data(): array {
		$data = [
			'tag'     => $this->options['btn_as_link'] ? 'a' : 'button',
			'url'     => $this->options['url'],
			'classes' => $this->get_classes(),
			'type'    => ! empty( $this->options['btn_as_link'] ) ? '' : $this->options['type'],
			'target'  => $this->options['target'],
			'attrs'   => '',
			'label'   => $this->options['label'],
		];

		return $data;
	}

	protected function get_classes() {
		$classes = [ 'btn' ];

		if ( ! empty( $this->options['classes'] ) ) {
			$classes = array_merge( $classes, $this->options['classes'] );
		}

		return implode( ' ', $classes );
	}

	protected function get_attrs() {

		if ( empty( $this->options['attrs'] ) ) {
			return '';
		}

		$attributes = $this->options['attrs'];

		$attrs = array_map( function ( $key ) use ( $attributes ) {
			if ( is_bool( $attributes[ $key ] ) ) {
				return $attributes[ $key ] ? $key : '';
			}

			return $key . '="' . esc_attr( $attributes[ $key ] ) . '"';
		}, array_keys( $attributes ) );

		return implode( ' ', $attrs );
	}

	/**
	 * Get an instance of this controller bound to the correct data.
	 *
	 * @param        $options
	 * @param string $template
	 *
	 * @return static
	 */
	public static function factory( $options, $template = 'components/button.twig' ) {
		return new static( $options, $template );
	}
}