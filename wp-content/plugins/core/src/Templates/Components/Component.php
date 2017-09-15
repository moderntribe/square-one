<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Theme\Util;
use Tribe\Project\Twig\Twig_Template;

abstract class Component extends Twig_Template {

	const TEMPLATE_NAME = '';

	protected $options = [];

	/**
	 * Component constructor.
	 *
	 * @param string      $template
	 * @param \Twig_Environment|null $twig
	 * @param array                  $options
	 */
	public function __construct( $template, \Twig_Environment $twig = null, $options ) {
		parent::__construct( $template, $twig );

		$this->options = $this->parse_options( $options );
	}

	abstract public function get_data(): array;

	abstract protected function parse_options( array $options ): array;

	protected function merge_classes( $default, $custom, $to_string = false ) {
		$classes = ! empty( $custom ) ? array_merge( $default, $custom ) : $default;

		if ( $to_string ) {
			$classes = implode( ' ', $classes );
		}

		return $classes;
	}

	protected function merge_attrs( $default, $custom, $to_string = false ) {
		$attrs = ! empty( $custom ) ? array_merge( $default, $custom ) : $default;

		if ( $to_string ) {
			$attrs = Util::array_to_attributes( $attrs );
		}

		return $attrs;
	}

	/**
	 * Get an instance of this controller bound to the correct data.
	 *
	 * @param array  $options
	 * @param string $template
	 *
	 * @return static
	 */
	public static function factory( $options, $template = '' ) {

		$template = empty( $template ) ? static::TEMPLATE_NAME : $template;
		$twig     = apply_filters( 'tribe/project/twig', null );

		return new static( $template, $twig, $options );
	}

}