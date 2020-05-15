<?php

namespace Tribe\Project\Service_Providers\iMiS;

use DateTime;
use stdClass;
use Tribe\Project\DB\Models\Example;
use Tribe\Project\DB\Query\DB_Query;
use Tribe\Tests\Test_Case;

class DB_QueryTest extends Test_Case {

	public function test_default_query() {
		$q       = new DB_Query();
		$results = $q->get_storables();

		/**
		 * Need to set these up in the DB first.
		 *
		 * $this->assertNotEmpty( $results );
		 * $this->assertInstanceOf( Example::class, $results[0] );
		 * $this->assertEquals( '2', $results[0]->ID );
		 */
	}

	public function test_default_query_w_wheres() {
		$q       = new DB_Query( [
			DB_Query::ID => 2,
		] );
		$results = $q->get_storables();

		/**
		 * Need to set these up in the DB first.
		 *
		 * $this->assertNotEmpty( $results );
		 * $this->assertInstanceOf( Example::class, $results[0] );
		 * $this->assertEquals( '2', $results[0]->ID );
		 */
	}

	public function test_example_query() {
		$q       = new DB_Query( [
			DB_Query::TYPE => Example::NAME,
		] );
		$results = $q->get_storables();

		/**
		 * Need to set these up in the DB first.
		 *
		 * $this->assertNotEmpty( $results );
		 * $this->assertInstanceOf( Example::class, $results[0] );
		 * $this->assertEquals( '12345', $results[0]->someprop );
		 */
	}

	public function test_insert_example() {
		$result = 0;
		$act    = new Example( 0, '', 'data' );

		$q      = new DB_Query( [
			DB_Query::TYPE => Example::NAME,
		] );
		$result = $q->insert_storable( $act );

		$this->assertEquals( 1, $result );
	}

	public function test_update_example() {
		$result = 0;

		/**
		 * Need to set these up in the DB first.
		 *
		$q      = new DB_Query( [
			DB_Query::TYPE   => Example::NAME,
			DB_Query::WHERES => [ Example::DATA => 'data' ],
		] );
		$result = $q->update_storable_field( Example::DATA, '54321' );

		$this->assertEquals( 1, $result );

		$q->reset_query_arg( DB_Query::WHERES );
		$storable = $q->get_storable();

		$this->assertEquals( '54321', $storable->data );
		 */
	}
}
