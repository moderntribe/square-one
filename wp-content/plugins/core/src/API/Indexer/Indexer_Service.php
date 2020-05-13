<?php


namespace Tribe\Project\API\Indexer;



use Tribe\Project\API\Indexed_Objects\Indexable;

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
