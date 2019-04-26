<?php

namespace Tribe\Project\Queues\Contracts;

use Tribe\Project\Queues\Message;

interface Backend {

	public function enqueue( string $queue_name, Message $m );

	/**
	 * @param string $queue_name
	 *
	 * @return Message
	 * @throws \RuntimeException if a Message could not be dequeued.
	 */
	public function dequeue( string $queue_name ): Message;

	/**
	 * @param string $job_id
	 * @param string $queue_name
	 *
	 * @return mixed
	 *
	 * Acknowledgement processing of the Message. This results in the task being removed from the
	 * queue.
	 */
	public function ack( string $job_id, string $queue_name );

	/**
	 * @param string $job_id
	 * @param string $queue_name
	 *
	 * @return mixed
	 *
	 * Negative Acknowledgement processing of the Message. This results in the task being returned
	 * to the queue.
	 */
	public function nack( string $job_id, string $queue_name );

	public function get_type(): string;

	public function count( string $queue_name ): int;

	public function cleanup();

	/**
	 * Asserts whether a Task is enqueued or not.
	 *
	 * @param string  $queue_name
	 * @param Message $message
	 *
	 * @return bool
	 */
	public function is_task_enqueued( string $queue_name, Message $message ) : bool;
}
