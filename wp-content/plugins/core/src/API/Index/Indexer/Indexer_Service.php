<?php
/**
 * Service that adds Indexable objects to an index.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Index\Indexer;

use Tribe\Project\API\Index\Models\Indexable;

/**
 * Interface Indexer_Service.
 */
interface Indexer_Service {

	/**
	 * Creates the index with dbDelta.
	 *
	 * @return bool
	 */
	public function index_upgrade(): bool;

	/**
	 * Save an object to the index.
	 *
	 * @param Indexable $indexable The indexable object.
	 */
	public function save( Indexable $indexable ): void;

	/**
	 * Delete an object from the index.
	 *
	 * @param Indexable $indexable The indexable object.
	 */
	public function delete( Indexable $indexable ): void;
}
