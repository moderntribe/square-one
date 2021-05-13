<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Global_Fields;

use Tribe\Libs\ACF\Traits\With_Get_Field;
use Tribe\Project\Blocks\Types\Model;

/**
 * Extend to override data being passed to existing models.
 *
 * @package Tribe\Project\Blocks\Global_Fields
 */
abstract class Block_Model implements Model {

	use With_Get_Field;

	/**
	 * The ACF Block ID.
	 *
	 * @var string
	 */
	protected string $block_id;

	/**
	 * A multidimensional array of model data.
	 *
	 * @return mixed[]
	 */
	abstract protected function set_data(): array;

	/**
	 * Assign an ACF block ID.
	 *
	 * @param string $block_id
	 *
	 * @return $this
	 */
	public function set_block_id( string $block_id ): self {
		$this->block_id = $block_id;

		return $this;
	}

	/**
	 * Retrieve model data.
	 *
	 * @return array|mixed[]
	 */
	public function get_data(): array {
		if ( empty( $this->block_id ) ) {
			return [];
		}

		return $this->set_data();
	}
}
