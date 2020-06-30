<?php

namespace Tribe\Project\Components;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Parser;
use Tribe\Project\Templates\Components\Component;
use Twig\Environment;
use Twig\Error\Error;

class Handler {

	/**
	 * @var Component_Factory
	 */
	private $factory;

	/**
	 * @var Parser
	 */
	private $parser;

	public function __construct( Component_Factory $factory, Parser $parser ) {
		$this->factory = $factory;
		$this->parser  = $parser;
	}

	public function render_component( string $component_name, array $args = [] ) {
		if ( class_exists( $component_name ) ) {
			$component = $this->factory->get( $component_name, $args );
		} else {
			$classname = $this->get_component_from_path( $component_name );
			$component = $this->factory->get( $classname, $args );
		}

		$component->output();
	}

	private function get_component_from_path( string $path ) {
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
