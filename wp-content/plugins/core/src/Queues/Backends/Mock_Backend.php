<?php


namespace Tribe\Project\Queues\Backends;


use Tribe\Project\Queues\Contracts\Backend;
use Tribe\Project\Queues\Message;

/**
 * Class Mock_Backend
 *
 * A trivial backend for use when running tests
 */
class Mock_Backend implements Backend {
	/** @var array[] */
	private $queues = [];

	public function enqueue( string $queue_name, Message $m ) {
		$this->queues[ $queue_name ][] = $m;
	}

	/**
	 * @param string $queue_name
	 *
	 * @return Message The first message in the queue. Nothing will be reserved.
	 */
	public function dequeue( string $queue_name ): Message {
		if ( array_key_exists( $queue_name, $this->queues ) && ! empty( $this->queues[ $queue_name ] ) ) {
			return reset( $this->queues[ $queue_name ] );
		}
		throw new \RuntimeException( 'No messages available to reserve.' );
	}

	public function ack( string $job_id, string $queue_name ) {
		return; // does nothing
	}

	public function nack( string $job_id, string $queue_name ) {
		return; // does nothing
	}

	public function get_type(): string {
		return self::class;
	}

	public function count( string $queue_name ): int {
		if ( array_key_exists( $queue_name, $this->queues ) && ! empty( $this->queues[ $queue_name ] ) ) {
			return count( $this->queues[ $queue_name ] );
		}

		return 0;
	}

	/**
	 * Resets the queue, deleting everything in it
	 *
	 * @return void
	 */
	public function cleanup() {
		$this->queues = [];
	}
}