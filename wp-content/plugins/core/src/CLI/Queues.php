<?php

namespace Tribe\Project\CLI;

use cli\Table;
use Tribe\Project\Queues\Contracts\Backend;
use Tribe\Project\Queues\Queue_Collection;
use Tribe\Project\Queues\Contracts\Queue;
use Tribe\Project\Queues\Tasks\Noop;

class Queues extends \WP_CLI_Command {

	protected $backend;
	protected $queues;

	public function __construct( Backend $backend, Queue_Collection $queues ) {
		$this->backend = $backend;
		$this->queues  = $queues;
		parent::__construct();
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

		if ( ! array_key_exists( $queue_name, $this->queues->queues() ) ) {
			\WP_CLI::error( __( 'That queue name doesn\'t appear to be valid.', 'tribe' ) );
		}

		$queue = $this->queues->get( $queue_name );

		for ( $i = 1; $i < $assoc_args['count']; $i ++ ) {
			$queue->dispatch( Noop::class, [ 'noop' => 'task' . microtime() ], $i );
		}
	}

	public function process( $args ) {

		if ( ! isset( $args[0] ) ) {
			\WP_CLI::error( __( 'You must specify which queue you wish to process.', 'tribe' ) );
		}

		$queue_name = $args[0];

		if ( ! array_key_exists( $queue_name, $this->queues->queues() ) ) {
			\WP_CLI::error( __( 'That queue name doesn\'t appear to be valid.', 'tribe' ) );
		}

		$queue = $this->queues->get( $queue_name );

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