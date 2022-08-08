<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Contracts;

/**
 * The Block Model Interface.
 */
interface Model {

	/**
	 * Set the data on a model.
	 *
	 * @return static
	 */
	public function set_data( array $data ): self;

	/**
	 * A multidimensional array of model data.
	 *
	 * @return mixed[]
	 */
	public function get_data(): array;

}
