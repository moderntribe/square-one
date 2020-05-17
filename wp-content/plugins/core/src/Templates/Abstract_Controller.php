<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates;

abstract class Abstract_Controller implements Controller_Interface {
	/**
	 * @var Component_Factory
	 */
	protected $factory;

	/**
	 * @param Component_Factory $factory
	 */
	public function __construct( Component_Factory $factory ) {
		$this->factory = $factory;
	}

}
