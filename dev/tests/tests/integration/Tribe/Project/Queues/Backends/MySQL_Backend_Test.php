<?php

namespace Tribe\Project\Queues\Backends;

use Codeception\TestCase\WPTestCase;
use Tribe\Project\Queues\Message;
use Tribe\Project\Queues\Tasks\Noop;
use Tribe\Tests\Queues\Test_Queue;

/**
 * Class MySQL_Backend_Test
 * @package Tribe\Project\Queues\Backends
 */
class MySQL_Backend_Test extends WPTestCase {

	const DB_TABLE = "queue";

	/** @var string Holds the prefixed table name */
	protected $table_name;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		global $wpdb;
		$this->table_name = $wpdb->prefix . self::DB_TABLE;
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/**
	 * Helper function to completely remove tasks from a given Queue from the Queues table.
	 *
	 * @param $queue_name
	 */
	protected function truncate_queue_table( $queue_name ) {
		global $wpdb;

		return $wpdb->delete( $this->table_name, [ 'queue' => $queue_name ] );
	}

	/** @test */
	public function should_create_a_table() {
		global $wpdb;
		$table_name = $wpdb->prefix . __FUNCTION__;
		add_filter( 'core_queues_backend_mysql_table_name', function () use ( $table_name ) {
			return $table_name;
		} );

		$create_queries = [];
		add_filter( 'dbdelta_create_queries', function ( $query ) use ( &$create_queries ) {
			$create_queries[] = $query;

			return $query;
		} );

		$mysql = new MySQL();
		$mysql->create_table();

		$this->assertInternalType( "array", $create_queries[0] );
		$this->assertArrayHasKey( $table_name, $create_queries[0] );
	}

	/** @test */
	public function should_enqueue_a_task() {
		$queue        = new Test_Queue( new MySQL() );
		$task_handler = Noop::class;
		$args         = [
			'test' => __METHOD__,
		];

		$this->truncate_queue_table( $queue::NAME );
		$queue->dispatch( $task_handler, $args );

		// Get the row.
		global $wpdb;
		$query_result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM $this->table_name
				WHERE queue = %s
				",
				$queue::NAME
			),
			ARRAY_A
		);

		$delete_count         = $this->truncate_queue_table( $queue::NAME );
		$query_result['args'] = json_decode( $query_result['args'], true );

		$this->assertEquals( 1, $delete_count );
		$this->assertInternalType( "array", $query_result['args'] );
		$this->assertEquals( __METHOD__, $query_result['args']['test'] );
	}

	/** @test */
	public function should_enqueue_multiple_tasks() {
		$queue           = new Test_Queue( new MySQL() );
		$amount_of_tasks = rand( 2, 10 );
		$this->truncate_queue_table( $queue::NAME );

		for ( $i = 1; $i <= $amount_of_tasks; $i ++ ) {
			$task_handler = Noop::class;
			$args         = [
				'test' => __METHOD__,
			];
			$queue->dispatch( $task_handler, $args );
		}

		global $wpdb;
		$query_result = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM $this->table_name
				WHERE queue = %s
				",
				$queue::NAME
			)
		);

		$this->truncate_queue_table( $queue::NAME );

		$this->assertEquals( $amount_of_tasks, $query_result );
	}

	/** @test */
	public function should_enqueue_a_task_with_a_message() {
		// Prepare the scenario
		global $wpdb;
		$backend      = new MySQL();
		$task_handler = Noop::class;
		$args         = [
			'test' => __METHOD__,
		];
		$message      = new Message( $task_handler, $args, 10 );
		$queue_name   = 'TestQueue';

		// Enqueue it.
		$backend->enqueue( $queue_name, $message );

		// Get the row.
		$queue = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM $this->table_name
				WHERE queue = %s
				",
				$queue_name
			),
			ARRAY_A
		);

		// Then delete it.
		$delete_count = $this->truncate_queue_table( $queue_name );

		$queue['args'] = json_decode( $queue['args'], true );

		$this->assertEquals( 1, $delete_count );
		$this->assertInternalType( "array", $queue['args'] );
		$this->assertEquals( __METHOD__, $queue['args']['test'] );
	}

	/** @test */
	public function should_assert_task_enqueued_returns_true_if_task_exists() {
		$queue        = new Test_Queue( new MySQL() );
		$task_handler = Noop::class;
		$args         = [
			'test' => __METHOD__,
		];

		$this->truncate_queue_table( $queue::NAME );
		$queue->dispatch( $task_handler, $args );
		$task_exists = $queue->is_task_enqueued( $task_handler, $args );

		$this->assertTrue( $task_exists );
	}

	/** @test */
	public function should_assert_task_enqueued_returns_false_if_queue_empty() {
		$queue        = new Test_Queue( new MySQL() );
		$task_handler = Noop::class;
		$args         = [
			'test' => __METHOD__,
		];

		$this->truncate_queue_table( $queue::NAME );
		$task_exists = $queue->is_task_enqueued( $task_handler, $args );

		$this->assertFalse( $task_exists );
	}

	/**
	 * @param array $different_tasks
	 *
	 * @dataProvider provider_different_tasks
	 * @test
	 */
	public function should_assert_task_enqueued_returns_false_if_different_tasks_exists( array $different_tasks ) {
		$queue        = new Test_Queue( new MySQL() );
		$task_handler = Noop::class;
		$args         = [
			'test' => 'this_is_the_original',
		];

		foreach ( $different_tasks as $different_task ) {
			$queue->dispatch( $task_handler, $different_task );
		}

		$task_exists = $queue->is_task_enqueued( $task_handler, $args );
		$this->truncate_queue_table( $queue::NAME );

		$this->assertFalse( $task_exists );
	}

	public function provider_different_tasks() {
		return [
			[
				// Scenario 1 - 1 task with different name
				[
					[
						'test' => 'Some different task',
					],
				],
			],
			[
				// Scenario 2 - 1 task with same name, but additional args
				[
					[
						'test' => 'this_is_the_original',
						'foo'  => 'bar',
					],
				],
			],
			[
				// Scenario 3 - 2 tasks
				[
					[
						'test' => '__METHOD__',
						'foo'  => 'bar',
					],
					[
						'test' => 'this_is_the_original',
						'foo'  => 'bar',
					],
				],
			],
			[
				// Scenario 4 - Empty test value
				[
					[
						'test' => '',
					],
				],
			],
			[
				// Scenario 5 - No "test" key
				[
					[
						'foo' => 'bar',
					],
				],
			],
		];
	}

	/** @test */
	public function should_assert_task_enqueued_returns_true_if_task_is_running() {
		global $wpdb;
		$queue        = new Test_Queue( new MySQL() );
		$task_handler = __METHOD__;
		$args         = [
			'return' => 'true',
			'sleep'  => 1,
		];
		$args_json    = json_encode( $args );

		$job_id = $wpdb->insert(
			$this->table_name,
			[
				'queue'        => $queue::NAME,
				'task_handler' => $task_handler,
				'args'         => $args_json,
				'priority'     => '10',
				'taken'        => time(),
			]
		);

		$is_task_enqueued = $queue->is_task_enqueued( $task_handler, $args );

		$this->truncate_queue_table( $queue::NAME );

		$this->assertTrue( $is_task_enqueued );
		$this->assertInternalType( "int", $job_id );
	}

	/** @test */
	public function should_assert_task_enqueued_returns_false_if_task_is_done() {
		global $wpdb;
		$queue        = new Test_Queue( new MySQL() );
		$task_handler = __METHOD__;
		$args         = [
			'return' => 'true',
			'sleep'  => 1,
		];
		$args_json    = json_encode( $args );

		$job_id = $wpdb->insert(
			$this->table_name,
			[
				'queue'        => $queue::NAME,
				'task_handler' => $task_handler,
				'args'         => $args_json,
				'priority'     => '10',
				'taken'        => time(),
				'done'         => time(),
			]
		);

		$is_task_enqueued = $queue->is_task_enqueued( $task_handler, $args );

		$this->truncate_queue_table( $queue::NAME );

		$this->assertFalse( $is_task_enqueued );
		$this->assertInternalType( "int", $job_id );
	}
}
