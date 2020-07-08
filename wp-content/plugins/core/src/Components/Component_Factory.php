<?php
declare( strict_types=1 );

namespace Tribe\Project\Components;

use Tribe\Project\Assets\Theme\Theme_Build_Parser;
use Tribe\Project\Components\Component;
use Twig\Environment;

/**
 * Class Component_Factory
 *
 * @package Tribe\Project\Components
 */
class Component_Factory {

	/**
	 * @var Handler
	 */
	protected $twig;

	/**
	 * @var Theme_Build_Parser
	 */
	protected $build_parser;

	/**
	 * Component_Factory constructor.
	 *
	 * @param Environment        $twig
	 * @param Theme_Build_Parser $build_parser
	 */
	public function __construct( Environment $twig, Theme_Build_Parser $build_parser ) {
		$this->twig         = $twig;
		$this->build_parser = $build_parser;
	}

	/**
	 * @param string $class
	 * @param array  $args
	 *
	 * @return Component
	 */
	public function get( string $class, array $args = [] ): Component {
		if ( ! is_subclass_of( $class, Component::class ) ) {
			throw new \InvalidArgumentException( 'Only Component subclasses may be requested from the Component factory' );
		}

		return new $class( $this, $this->twig, $this->build_parser, $args );
	}
}
