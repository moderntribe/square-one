<?php

namespace Tribe\Project\CLI;

use cli\Table;
use Pimple\Container;
use Tribe\Project\Queues\Contracts\Queue;
use Tribe\Project\Queues\Backends\Mysql;
use Tribe\Project\Queues\Tasks\Noop;

class Queues extends \WP_CLI_Command {

	protected $container = null;

	public function __construct( Container $container ) {
		$this->container = $container;
		parent::__construct();
	}

	/**
	 * ## EXAMPLES
	 *
	 *     wp queues list
	 *
	 * @when after_wp_load
	 */
	public function list() {
		$queues = [];
		foreach ( Queue::instances() as $queue ) {
			/** @var Queue $queue */

			$parts    = explode( '\\', $queue->get_backend_type() );
			$queues[] = [
				'Queue'        => $queue->get_name(),
				'Backend'      => end( $parts ),
				'Pending Jobs' => $queue->count(),
			];
		}


		$table = new Table( $queues );
		$table->display();

	}

	public function add_table() {
		global $wpdb;

		$table_exists = $wpdb->query( $wpdb->prepare(
			'SHOW TABLES LIKE %s',
			$wpdb->prefix . Mysql::DB_TABLE
		) );

		// Create table.
		if ( ! $table_exists ) {
			$wpdb->query( sprintf (
				'CREATE TABLE %s (
					id bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
					queue varchar(255) NOT NULL,
					task_handler varchar(255) NOT NULL,
					args text NOT NULL,
					priority int(3) NOT NULL,
					taken int(10) 
				)',
				$wpdb->prefix . Mysql::DB_TABLE
			) );

			\WP_CLI::success( __( 'Task table successfully created.', 'tribe' ) );
			return;
		}

		\WP_CLI::success( __( 'Task table already exists.', 'tribe' ) );
	}

	public function add_tasks() {
		$task_count = rand( 1, 50 );
		for ( $i = 1; $i < $task_count; $i ++ ) {
			$this->container['queues.TestingQueue']->dispatch( Noop::class, [ 'fake' => 'task' . $i ], $i );
		}
	}

	public function process( $args ) {

		if ( ! isset( $args[0] ) ) {
			\WP_CLI::error( __( 'You must specify which queue you wish to process.', 'tribe' ) );
		}

		foreach( Queue::instances() as $queue ) {
			if ( $queue->get_name() === $args[0] ) {
				$queue_class = get_class( $queue );
				$backend_class = $queue->get_backend_type();
			}
		}

		if ( ! class_exists( $queue_class ) || !class_exists( $backend_class ) ) {
			\WP_CLI::error( __( 'The queue and/or backend in question could not be found.', 'tribe' ) );
		}

		$tasks = new $queue_class( new $backend_class() );

		// Run forever...or until the queue is empty.
		while ( 0 != $tasks->count() ) {
			$job = $tasks->reserve();

			$task_class = $job->get_task_handler();
			if ( ! class_exists( $task_class ) ) {
				return;
			}
			$task = new $task_class();

			if ( $task->handle( $job->get_args() ) ) {
				// Acknowledge.
				$queue->ack( $job->get_job_id() );
			} else {
				$queue->nack( $job->get_job_id() );
			}
		}

	}

}