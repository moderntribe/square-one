<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Controllers\Block;

use Tribe\Gutenpanels\Blocks\Block_Type;
use Tribe\Project\Templates\Abstract_Controller;
use Tribe\Project\Templates\Component_Factory;

abstract class Block_Controller extends Abstract_Controller {
	/**
	 * @var array The attributes parsed from the block
	 */
	protected $attributes;

	/**
	 * @var string The contents parsed from the block
	 */
	protected $content;

	/**
	 * @var Block_Type The registered block type
	 */
	protected $block_type;

	public function __construct( Component_Factory $factory, array $attributes, string $content, Block_Type $block_type ) {
		parent::__construct( $factory );
		$this->attributes = $attributes;
		$this->content    = $content;
		$this->block_type = $block_type;
	}
}
