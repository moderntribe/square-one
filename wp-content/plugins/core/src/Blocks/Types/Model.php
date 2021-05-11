<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Types;

/**
 * The Block Model Interface.
 *
 * @package Tribe\Project\Blocks\Types
 */
interface Model {

	/**
	 * A multidimensional array of model data.
	 *
	 * @return mixed[]
	 */
	public function get_data(): array;
}
