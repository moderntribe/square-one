<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware\Pipelines;

use Tribe\Project\Block_Middleware\Contracts\Block_Pipeline;
use Tribe\Project\Blocks\Contracts\Model;

/**
 * Processes pipeline stages to add data to block models.
 */
class Add_Model_Data_Pipeline extends Block_Pipeline {

	public function process( Model $model ): Model {
		return $this->pipeline->send( $model )->thenReturn();
	}

}
