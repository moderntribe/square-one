<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware\Pipelines;

use Tribe\Libs\ACF\Block_Config;
use Tribe\Project\Block_Middleware\Contracts\Block_Pipeline;

/**
 * Processes pipeline stages to add additional fields to a block.
 */
class Add_Fields_Pipeline extends Block_Pipeline {

	/**
	 * @param \Tribe\Libs\ACF\Block_Config $block The block to be processed through the middleware pipeline.
	 * @param array                        $params Optional parameters that get passed to each stage.
	 *
	 * @return \Tribe\Libs\ACF\Block_Config
	 */
	public function process( Block_Config $block, array $params = [] ): Block_Config {
		return $this->pipeline->send( $block, $params )->thenReturn();
	}

}
