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
			$table_name = $wpdb->prefix . Mysql::DB_TABLE;
			$wpdb->query(
				"CREATE TABLE $table_name (
					id bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
					queue varchar(255) NOT NULL,
					task_handler varchar(255) NOT NULL,
					args text NOT NULL,
					priority int(3),
					taken int(10) NOT NULL DEFAULT 0
				)"
			);

			\WP_CLI::success( __( 'Task table successfully created.', 'tribe' ) );
			return;
		}

		\WP_CLI::success( __( 'Task table already exists.', 'tribe' ) );
	}

	/**
	 * ## OPTIONS
	 *
	 * <queue_name>
	 * : The name of the queue to add the tasks to.
	 *
	 * [--count=<count>]
	 * : How many tasks to add.
	 */
	public function add_tasks( $args, $assoc_args ) {

		if ( ! isset( $assoc_args['count'] ) ) {
			$assoc_args['count'] = rand( 1, 50 );
		}

		$queue_name = $args[0];

		if ( ! array_key_exists( $queue_name, Queue::instances() ) ) {
			\WP_CLI::error( __( 'That queue name doesn\'t appear to be valid.', 'tribe' ) );
		}

		$queue = Queue::get_instance( $queue_name );


		for ( $i = 1; $i < $assoc_args['count']; $i ++ ) {
			$queue->dispatch( Noop::class, [ 'noop' => 'task' . microtime() ], $i );
		}
	}

	public function process( $args ) {

		if ( ! isset( $args[0] ) ) {
			\WP_CLI::error( __( 'You must specify which queue you wish to process.', 'tribe' ) );
		}

		$queue_name = $args[0];

		if ( ! array_key_exists( $queue_name, Queue::instances() ) ) {
			\WP_CLI::error( __( 'That queue name doesn\'t appear to be valid.', 'tribe' ) );
		}

		$queue = Queue::get_instance( $queue_name );

		// Run forever...or until the queue is empty.
		while ( 0 != $queue->count() ) {
			$job = $queue->reserve();

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