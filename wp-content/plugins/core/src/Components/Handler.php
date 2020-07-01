<?php

namespace Tribe\Project\Components;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Parser;

/**
 * Class Handler
 *
 * @package Tribe\Project\Components
 */
class Handler {

	/**
	 * @var Component_Factory
	 */
	private $factory;

	/**
	 * @var Parser
	 */
	private $parser;

	/**
	 * Handler constructor.
	 *
	 * @param Component_Factory $factory
	 * @param Parser            $parser
	 */
	public function __construct( Component_Factory $factory, Parser $parser ) {
		$this->factory = $factory;
		$this->parser  = $parser;
	}

	/**
	 * @param string $component_name
	 * @param array  $args
	 */
	public function render_component( string $component_name, array $args = [] ) {
		/**
		 * Allows plugins to perform actions before a component is rendered.
		 *
		 * @param string $component_name - The name or path of the current component
		 * @param array $args - the arguments being passed to the component
		 */
		do_action( 'tribe/project/components/before_render', $component_name, $args );

		if ( class_exists( $component_name ) ) {
			$component = $this->factory->get( $component_name, $args );
		} else {
			$classname = $this->get_component_from_path( $component_name );
			$component = $this->factory->get( $classname, $args );
		}

		$component->output();

		/**
		 * Allows plugins to perform actions after a component is rendered.
		 *
		 * @param string $component_name - The name or path of the current component
		 * @param array $args - the arguments being passed to the component
		 */
		do_action( 'tribe/project/components/after_render', $component_name, $args );
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	private function get_component_from_path( string $path ) {
		/**
		 * Allows plugins to modify the path passed before attempting to locate it.
		 *
		 * @param string $path - the current path being loaded.
		 */
		$path = apply_filters( 'tribe/project/components/component_path', $path );

		$full_path = sprintf( '%s%s%s', trailingslashit( get_stylesheet_directory() ), 'components/', $path );

		if ( ! file_exists( $full_path ) ) {
			throw new \InvalidArgumentException( 'Could not locate component at ' . $full_path );
		}

		$basename = basename( $full_path, '.php' );
		$ast      = $this->parser->parse( file_get_contents( $full_path ) );

		foreach ( $ast as $node ) {
			if ( ! is_a( $node, Namespace_::class ) ) {
				continue;
			}

			/**
			 * @var Name $name
			 */
			$name = $node->name;

			return sprintf( '%s\%s', $name->toString(), $basename );
		}

		throw new \InvalidArgumentException( 'Component ' . $full_path . ' does not appear to be a fully-qualified class.' );
	}

}
