<?php declare(strict_types=1);

namespace Tribe\Tests\Fixtures;

use Tribe\Project\Block_Middleware\Contracts\Abstract_Model_Middleware;
use Tribe\Project\Blocks\Contracts\Model;

class Description_Model_Middleware extends Abstract_Model_Middleware {

	protected function append_data( Model $model ): Model {
		$data = [
			'description' => 'This is new data',
		];

		return $model->set_data( array_replace_recursive( $model->get_data(), $data ) );
	}

}
