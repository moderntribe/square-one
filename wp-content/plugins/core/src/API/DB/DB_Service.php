<?php
/**
 * The database client service interface.
 *
 * @package Square1-API
 */
declare(strict_types=1);

namespace Tribe\Project\API\DB;

/**
 * Interface DB_Service.
 */
interface DB_Service {

	/**
	 * Run a SQL query.
	 *
	 * @param string $query The SQL query statement.
	 *
	 * @return mixed
	 */
	public function query( string $query );
}
