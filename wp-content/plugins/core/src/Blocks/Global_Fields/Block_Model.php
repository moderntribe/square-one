<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Global_Fields;

use Tribe\Project\Blocks\Traits\With_Get_Field;
use Tribe\Project\Blocks\Types\Model;

abstract class Block_Model implements Model {

	use With_Get_Field;

	abstract protected function set_data(): array;

	public function get_data(): array {
		return $this->set_data();
	}
}
