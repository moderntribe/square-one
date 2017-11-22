<?php

namespace Tribe\Project\Queues\Contracts;

use Tribe\Project\Queues\Message;

abstract class Queue {

	protected $backend;
	protected static $instances = [];

	abstract public function get_name(): string;

	public function __construct( Backend $backend ) {
		$this->backend     = $backend;
		self::$instances[ $this->get_name() ] = $this;
	}

	public function get_backend_type(): string {
		return $this->backend->get_type();
	}

	public function dispatch( string $task_handler, array $args = [], $priority = 10 ) {
		$message = new Message( $task_handler, $args, $priority );
		$this->backend->enqueue( $this->get_name(), $message );
	}

	public function reserve(): Message {
		$message = $this->backend->dequeue( $this->get_name() );

		return $message;
	}

	public function count():int{
		return $this->backend->count( $this->get_name() );
	}

	public function ack( $job_id ) {
		$this->backend->ack( $job_id, $this->get_name() );
	}

	public function nack( $job_id ) {
		$this->backend->nack( $job_id, $this->get_name() );
	}

	public static function instances(): array {
		return self::$instances;
	}

	public static function get_instance( $queue_name ) {
		if ( isset( self::instances()[ $queue_name ] ) ) {
			return self::instances()[ $queue_name ];
		}

		return false;
	}

	public static function instance(): Queue {
		return tribe_project()->container()[ 'queues.' . __CLASS__ ];
	}

}
