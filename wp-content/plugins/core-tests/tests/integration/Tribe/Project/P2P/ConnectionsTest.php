<?php

namespace Tribe\Project\P2P;

use Tribe\Project\P2P\Relationships\General_Relationship;
use Tribe\Project\P2P\Relationships\Sample_To_Page;
use Tribe\Project\Post_Types\Sample\Sample;
use Tribe\Project\Post_Types\Page\Page;

use Codeception\TestCase\WPTestCase;

/**
 * Class ConnectionsTest
 * @package Tribe\Project\P2P
 */
class ConnectionsTest extends WPTestCase {

	private $connection_id;
	private $sample_id;
	private $page_id;

	public function setUp() {
		// before
		parent::setUp();

		$this->sample_id = wp_insert_post( [
			'post_title' => 'Test Sample Post',
			'post_status' => 'publish',
			'post_type' => Sample::NAME,
		] );

		$this->page_id = wp_insert_post( [
			'post_title' => 'Test Page',
			'post_status' => 'publish',
			'post_type' => Page::NAME,
		] );

		$connect = [ 'from' => $this->sample_id, 'to' => $this->page_id ];
		$this->connection_id = p2p_create_connection( Sample_To_Page::NAME, $connect );
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * Test retrieving connections of specific type and from post id
	 */
	public function get_connection_test() {
		$connections = tribe_project()->container()['p2p.connections'];
		$ids = $connections->get_from(
			$this->sample_id,
			[
				'type' => [
					Sample_To_Page::NAME,
				],
			]
		);

		$this->assertTrue( 1 === $ids );
	}

	public function setup_multiple_type_connections() {

	}

}