<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks;

use Tribe\Gutenpanels\Blocks\Block_Type;
use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Controllers;

class Render_Filter {
	/**
	 * @var Component_Factory
	 */
	private $factory;

	/**
	 * @var array A map of block type names to the Block_Controller class used to render it
	 */
	private $map;


	public function __construct( Component_Factory $factory, array $map ) {
		$this->factory = $factory;
		$this->map     = $map;
	}

	public function render( string $prefiltered, array $attributes, string $content, Block_Type $block_type ) {
		$name = $block_type->name()->value();

		if ( ! array_key_exists( $name, $this->map ) ) {
			return $prefiltered;
		}

		/** @var Controllers\Block\Block_Controller $controller */
		$controller = new $this->map[$name]( $this->factory, $attributes, $content, $block_type );

		return $controller->render();
	}
}
