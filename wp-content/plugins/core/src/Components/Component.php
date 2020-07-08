<?php

namespace Tribe\Project\Components;

use Tribe\Libs\Utils\Markup_Utils;
use DI;
use Tribe\Project\Assets\Theme\Theme_Build_Parser;
use Tribe\Project\Components\Component_Factory;
use Tribe\Project\Components\Handler;
use Tribe\Project\Models\Model;
use Tribe\Project\Models\Post;
use Twig\Environment;
use Twig\Error\Error;

abstract class Component {

	/**
	 * @var bool
	 */
	protected $debug = false;

	/**
	 * @var bool
	 */
	protected $truncate_debug_models = true;

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

	/**
	 * @var string
	 */
	protected $twig_file = '';

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

	public function render(): void {
		$twig_file = $this->get_twig_file_for_component();

		include( $twig_file );
	}

	protected function get_twig_file_for_component() {
		$dir = dirname( ( new \ReflectionClass( static::class ) )->getFileName() );

		if ( ! empty( $this->twig_file ) ) {
			return sprintf( '%s/%s', $dir, $this->twig_file );
		}

		$files = scandir( $dir );
		$twigs = array_filter( $files, function ( $file ) {
			$ext = pathinfo( $file, PATHINFO_EXTENSION );

			return $ext === 'twig';
		} );

		if ( empty( $twigs ) ) {
			throw new \ErrorException( 'Could not locate a valid Twig template for this component. Please ensure a .twig file exists in the same directory, or that you explicitely define the $twig_file property for this component.' );
		}

		return sprintf( '%s/%s', $dir, basename( array_shift( $twigs ) ) );
	}

	public function output(): void {
		/**
		 * @var Component $component
		 */
		$this->init();

		if ( $this->debug ) {
			$this->print_debug_info();
		}

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

	protected function print_debug_info() {
		$debug_data = $this->get_debug_data_recursive( $this->data );
		echo '<pre>';
		print_r( $debug_data );
		echo '</pre>';
	}

	protected function get_debug_data_recursive( $data ) {
		$return = [];
		foreach ( $data as $key => $value ) {
			if ( $this->truncate_debug_models && is_a( $value, Model::class ) ) {
				$return[ $key ] = get_class( $value );
				continue;
			}

			if ( is_array( $value ) ) {
				$return[ $key ] = $this->get_debug_data_recursive( $value );
				continue;
			}

			$return[ $key ] = $value;
		}

		return $return;
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


}
