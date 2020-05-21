<?php
/**
 * Fetch records from an index.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Index\Fetcher;

/**
 * Interface Fetcher_Service.
 */
interface Fetcher_Service {

	/**
	 * Return an array of records.
	 *
	 * @return array
	 */
	public function get_results(): array;

	/**
	 * Uses an object ID to get a single object.
	 *
	 * @param int $id The ID.
	 *
	 * @return mixed
	 */
	public function get_single_result( int $id );
}
