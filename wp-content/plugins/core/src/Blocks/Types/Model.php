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

	/**
	 * Retrieve data from an ACF field.
	 *
	 * @param int|string $key ACF key identifier.
	 * @param false  $default The default value if the field doesn't exist.
	 *
	 * @return mixed
	 */
	public function get( $key, bool $default = false );

	/**
	 * Get the "Additional Class Names" value from the block editor.
	 *
	 * @return string[]
	 */
	public function get_classes(): array;
}
