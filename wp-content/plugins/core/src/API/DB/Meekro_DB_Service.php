<?php

namespace Tribe\API\DB;

/**
 * Class Meekro_DB_Service
 *
 * @package Tribe\API\DB
 */
class Meekro_DB_Service implements DB_Service {

	/**
	 * @var \MeekroDB The DB instance.
	 */
	private $db;

	/**
	 * Meekro_DB_Service constructor.
	 *
	 * @param \MeekroDB $db
	 */
	public function __construct( \MeekroDB $db ) {
		$this->db = $db;

		// Set MeekroDB to throw exceptions when the query fails.
		$this->db->throw_exception_on_error = true;
		$this->db->error_handler            = false;
		$this->db->encoding                 = 'utf8';
	}

	/**
	 * Run the SQL query.
	 *
	 * @param string $query The query string.
	 *
	 * @return mixed
	 */
	public function query( string $query ) {
		return $this->db->query( $query );
	}
}
