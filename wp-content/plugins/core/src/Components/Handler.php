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
	 * @var Environment
	 */
	private $twig;

	/**
	 * @var Parser
	 */
	private $parser;

	public function __construct( Environment $twig, Parser $parser ) {
		$this->twig   = $twig;
		$this->parser = $parser;
	}

	public function render_component( string $component_name, array $args = [] ) {
		if ( class_exists( $component_name ) ) {
			$component = new $component_name( $args );
		} else {
			$classname = $this->get_component_from_path( $component_name );
			$component = new $classname( $args );
		}

		if ( ! is_a( $component, Component::class ) ) {
			throw new \InvalidArgumentException( 'Component ' . $component_name . ' does not appear to be a valid Component class.' );
		}

		/**
		 * @var Component $component
		 */
		$component->init();

		ob_start();
		$component->render();
		$template = ob_get_clean();

		try {
			echo $this->twig->render( $this->twig->createTemplate( $template ), $component->data() );
		} catch ( Error $e ) {
			error_log( $e->getMessage() );
			return;
		}
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
