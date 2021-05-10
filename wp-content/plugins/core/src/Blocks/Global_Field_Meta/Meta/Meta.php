<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Global_Field_Meta\Meta;

use Tribe\Libs\ACF\Field;

/**
 * Meta interface that returns an array of Field objects.
 *
 * @package Tribe\Project\Blocks\Global_Field_Meta
 */
interface Meta {

	/**
	 * @return Field[]
	 */
	public function get_fields(): array;
}
