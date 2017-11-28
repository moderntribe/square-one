<?php

namespace Tribe\Project\CLI;

use cli\Table;
use Tribe\Project\Queues\Contracts\Backend;
use Tribe\Project\Queues\Contracts\Queue;
use Tribe\Project\Queues\Tasks\Noop;

class Queues extends \WP_CLI_Command {

	protected $backend;

	public function __construct( Backend $container ) {
		$this->backend = $container;
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
		if ( ! 'MySQL' === get_class( $this->backend ) ) {
			\WP_CLI::error( __( 'You cannot add a table a non-MySQL backend' ) );
		}

		if ( $this->backend->table_exists() ) {
			\WP_CLI::success( __( 'Task table already exists.', 'tribe' ) );
			return;
		}

		$this->backend->create_table();
		\WP_CLI::success( __( 'Task table successfully created.', 'tribe' ) );

	}

	public function cleanup( $args ) {
		if ( ! isset( $args[0] ) ) {
			\WP_CLI::error( __( 'You must specify which queue you wish to process.', 'tribe' ) );
		}

		$queue_name = $args[0];

		if ( ! array_key_exists( $queue_name, Queue::instances() ) ) {
			\WP_CLI::error( __( 'That queue name doesn\'t appear to be valid.', 'tribe' ) );
		}

		$queue = Queue::get_instance( $queue_name );

		$queue->cleanup();
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

		// Run forever.
		while ( 1 ) {

			// If the queue is empty, sleep on it and then clear it again.
			if ( ! $queue->count() ) {
				sleep( 1 );
				continue;
			}

			$job = $queue->reserve();

			$task_class = $job->get_task_handler();

			if ( ! class_exists( $task_class ) ) {
				$queue->nack( $job->get_job_id() );
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