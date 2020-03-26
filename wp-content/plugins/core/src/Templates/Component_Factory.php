<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates;

use Tribe\Project\Templates\Components\Component;
use Twig\Environment;

class Component_Factory {
	/**
	 * @var Environment
	 */
	private $twig;

	public function __construct( Environment $twig ) {
		$this->twig = $twig;
	}

	/**
	 * @param string $class
	 * @param array  $options
	 * @param mixed  ...$args
	 *
	 * @return Component
	 */
	public function get( string $class, $options, ...$args ): Component {
		if ( ! is_subclass_of( $class, Component::class ) ) {
			throw new \InvalidArgumentException( 'Only Component subclasses may be requested from the Component factory' );
		}

		return new $class( $this->twig, $this, $options, ...$args );
	}
}
