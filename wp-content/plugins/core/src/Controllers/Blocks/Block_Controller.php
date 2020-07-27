<?php

namespace Tribe\Project\Controllers\Blocks;

use Tribe\Project\Controllers\Controller;

abstract class Block_Controller extends Controller {

	protected $attributes;

	protected $content;

	protected $block_type;

	abstract public function render( $attributes, $content, $block_type );

}
