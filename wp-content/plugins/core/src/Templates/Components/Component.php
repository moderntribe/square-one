<?php

namespace Tribe\Project\Templates\Components;

use Tribe\Libs\Utils\Markup_Utils;
use DI;
use Tribe\Project\Assets\Theme\Theme_Build_Parser;
use Tribe\Project\Components\Component_Factory;
use Tribe\Project\Components\Handler;
use Twig\Environment;
use Twig\Error\Error;

abstract class Component {

	/**
	 * @var array
	 */
	protected $data;

	/**
	 * @var Component_Factory
	 */
	protected $factory;

	/**
	 * @var Environment
	 */
	protected $twig;

	/**
	 * @var Theme_Build_Parser
	 */
	protected $build_parser;

	public function __construct( Component_Factory $factory, Environment $twig, Theme_Build_Parser $build_parser, $args = [] ) {
		$this->factory      = $factory;
		$this->twig         = $twig;
		$this->build_parser = $build_parser;

		$this->data = $args;
		$this->merge_defaults();
	}

	private function merge_defaults() {
		$this->data = wp_parse_args( $this->data, $this->defaults() );
	}

	public function data(): array {
		return $this->data;
	}

	public function output(): void {
		/**
		 * @var Component $component
		 */
		$this->init();

		ob_start();
		$this->render();
		$template = ob_get_clean();

		try {
			echo $this->twig->render( $this->twig->createTemplate( $template ), $this->data() );
		} catch ( Error $e ) {
			error_log( $e->getMessage() );

			return;
		}
	}

	public function get_rendered_output(): string {
		ob_start();
		$this->output();

		return ob_get_clean();
	}

	/**
	 * Merge associative arrays of attributes into a string
	 *
	 * @param array ...$attributes
	 *
	 * @return string
	 */
	protected function merge_attrs( array ...$attributes ): string {
		$attributes = empty( $attributes ) ? [] : array_merge( ... $attributes );

		return Markup_Utils::concat_attrs( $attributes );
	}

	/**
	 * Sanitize and merge classes into a string for inclusion in a class attribute
	 *
	 * @param array ...$classes
	 *
	 * @return string
	 */
	protected function merge_classes( array ...$classes ): string {
		return Markup_Utils::class_attribute( array_merge( ... $classes ), true );
	}

	public function init() {
	}

	protected function defaults(): array {
		return [];
	}

	abstract public function render(): void;

}
