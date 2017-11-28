<?php

namespace Tribe\Project\CLI;

use cli\Table;
use Tribe\Project\Queues\Queue_Collection;
use Tribe\Project\Queues\Tasks\Noop;

class Queues extends \WP_CLI_Command {

	protected $mysql;

	public function __construct( Container $container ) {
		$this->mysql = $container;
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
		foreach ( Queue_Collection::instances() as $queue ) {
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
		if ( $this->mysql->table_exists() ) {
			\WP_CLI::success( __( 'Task table already exists.', 'tribe' ) );
			return;
		}

		$this->mysql->create_table();
		\WP_CLI::success( __( 'Task table successfully created.', 'tribe' ) );

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

		if ( ! array_key_exists( $queue_name, Queue_Collection::instances() ) ) {
			\WP_CLI::error( __( 'That queue name doesn\'t appear to be valid.', 'tribe' ) );
		}

		$queue = Queue_Collection::get_instance( $queue_name );


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

		$queue = Queue_Collection::get_instance( $queue_name );

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