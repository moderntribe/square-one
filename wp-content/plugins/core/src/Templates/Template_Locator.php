<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates;

use Pimple\Container;

class Template_Locator {
	/**
	 * @var Container
	 */
	private $container;

	public function __construct( Container $container ) {
		$this->container = $container;
	}

	public function locate( $path ): Template_Interface {
		$key = 'template:' . $path;
		if ( ! isset( $this->container[ $key ] ) ) {
			throw new Template_Not_Found_Exception( sprintf( 'No template controller found for %s', $path ) );
		}
		return $this->container[ $key ];
	}
}
