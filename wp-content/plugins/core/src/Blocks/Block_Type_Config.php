<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks;

use Tribe\Gutenpanels\Blocks\Block_Type_Interface;
use Tribe\Gutenpanels\Builder\Factories\Builder_Factory;

abstract class Block_Type_Config {
	/** @var Builder_Factory */
	protected $factory;

	public function __construct( Builder_Factory $factory ) {
		$this->factory = $factory;
	}

	abstract public function build(): Block_Type_Interface;
}
