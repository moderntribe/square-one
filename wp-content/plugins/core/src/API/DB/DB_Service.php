<?php

namespace Tribe\Project\API\DB;

/**
 * Interface DB_Service
 *
 * @package Tribe\API\DB
 */
interface DB_Service {

	/**
	 * @param string $query
	 *
	 * @return mixed
	 */
	public function query( string $query );
}
