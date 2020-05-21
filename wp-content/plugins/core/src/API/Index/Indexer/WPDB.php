<?php
/**
 * WPDb-based indexing service.
 *
 * @package Square1-API
 */
declare( strict_types=1 );

namespace Tribe\Project\API\Index\Indexer;

use Tribe\Project\API\Index\Models\Indexable;

/**
 * Class WPDB.
 */
class WPDB implements Indexer_Service {

	/**
	 * @inheritDoc
	 */
	public function index_upgrade(): bool {
		// TODO: Implement index_upgrade() method.
	}

	/**
	 * @inheritDoc
	 */
	public function save( Indexable $indexable ): void {
		// TODO: Implement save() method.
	}

	/**
	 * @inheritDoc
	 */
	public function delete( Indexable $indexable ): void {
		// TODO: Implement delete() method.
	}
}
