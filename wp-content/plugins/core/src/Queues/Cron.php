<?php


namespace Tribe\Project\Queues;


use Tribe\Project\Queues\Contracts\Queue;
use Tribe\Project\Queues\Contracts\Task;

class Cron {
	const CRON_ACTION = 'tribe_queue_cron';
	const FREQUENCY   = 'tribe_queue_frequency';

	private $frequency_in_seconds = 60;
	private $timelimit_in_seconds = 15;

	public function __construct( $frequency = 60, $timelimit = 15 ) {
		$this->frequency_in_seconds = $frequency;
		$this->timelimit_in_seconds = $timelimit;
	}

	/**
	 * @return void
	 * @action self::CRON_ACTION
	 */
	public function process_queues( Queue $queue ) {
		$end_time = time() + $this->timelimit_in_seconds;

		$queue->cleanup();

		while ( time() < $end_time ) {

			if ( ! $queue->count() ) {
				return;
			}

			try {
				$job = $queue->reserve();
			} catch ( \Exception $e ) {
				return;
			}

			$task_class = $job->get_task_handler();

			if ( ! class_exists( $task_class ) ) {
				$queue->nack( $job->get_job_id() );

				return;
			}

			/** @var Task $task */
			$task = new $task_class();

			try {
				// Execute the task. If it returns false, we will consider it failed.
				$task_succeeded = $task->handle( $job->get_args() );
			} catch ( \Exception $e ) {
				// If it throws, we will consider it failed.
				$task_succeeded = false;
			}

			if ( $task_succeeded === true ) {
				// Acknowledge.
				$queue->ack( $job->get_job_id() );
			} else {
				$queue->nack( $job->get_job_id() );
			}
		}
	}

	/**
	 * @return void
	 * @action admin_init
	 */
	public function schedule_cron() {
		if ( ! wp_next_scheduled( self::CRON_ACTION ) ) {
			wp_schedule_event( time(), self::FREQUENCY, self::CRON_ACTION );
		}
	}

	/**
	 * @param array $cron_schedules
	 *
	 * @return array
	 * @filter cron_schedules
	 */
	public function add_interval( $cron_schedules ) {
		$cron_schedules[ self::FREQUENCY ] = [
			'interval' => $this->frequency_in_seconds,
			'display'  => __( 'Queue Cron Schedule', 'tribe' ),
		];

		return $cron_schedules;
	}
}