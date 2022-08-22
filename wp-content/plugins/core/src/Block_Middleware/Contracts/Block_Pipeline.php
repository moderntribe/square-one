<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware\Contracts;

use Tribe\Libs\Pipeline\Contracts\Pipeline;

/**
 * Runs a block pipeline strategy.
 */
abstract class Block_Pipeline {

	protected Pipeline $pipeline;

	public function __construct( Pipeline $pipeline ) {
		$this->pipeline = $pipeline;
	}

}
