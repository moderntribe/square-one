<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Project\Templates\Abstract_Template;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Theme\Util;
use Twig\Environment;

abstract class Component extends Abstract_Template {

	const TEMPLATE_NAME = '';

	protected $options = [];

	/**
	 * @param Environment       $twig
	 * @param Component_Factory $factory
	 * @param array             $options
	 */
	public function __construct( Environment $twig, Component_Factory $factory, $options = [] ) {
		parent::__construct( $twig, $factory );

		$this->options = $this->parse_options( $options );
	}

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

	protected function get_path(): string {
		$path = parent::get_path();
		if ( strpos( $path, '/' ) === 0 ) {
			$search = array_unique( [ get_stylesheet_directory(), get_template_directory() ] );
			$path   = str_replace( $search, '', $path );
		}

		return $path;
	}

}
