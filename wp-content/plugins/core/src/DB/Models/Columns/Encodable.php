<?php
/**
 * Interface for column data types.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\DB\Models\Columns;

/**
 * Interface Columnated.
 */
interface Encodable {
	/**
	 * Prepare the value, including encoding, serialization, etc.
	 *
	 * @param mixed $value The raw value.
	 *
	 * @return mixed Encoded value.
	 */
	public function encode( $value );

	/**
	 * Decode a prepared value.
	 *
	 * @param mixed $value The prepared value.
	 *
	 * @return mixed The raw value.
	 */
	public function decode( $value );
}
