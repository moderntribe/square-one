<?php

namespace Tribe\Project\CLI\Queues;

use Tribe\Project\CLI\Command;
use Tribe\Project\Queues\Queue_Collection;

class Process extends Command {

	/**
	 * @var Queue_Collection
	 */
	protected $queues;

	public function __construct( Queue_Collection $queue_collection ) {
		$this->queues = $queue_collection;
		parent::__construct();
	}

	public function command() {
		return 'queues process';
	}

	public function description() {
		return __( 'Process the queue for the provided queue name.', 'tribe' );
	}

	public function arguments() {
		return [
			[
				'type'        => 'positional',
				'name'        => 'queue',
				'optional'    => false,
				'description' => __( 'The name of the Queue.', 'tribe' ),
			],
		];
	}

	public function run_command( $args, $assoc_args ) {
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