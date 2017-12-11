<?php

namespace Tribe\Project\Queues;

use Tribe\Project\Queues\Contracts\Queue;

class Queue_Collection {

	protected $instances = [];

	public function add( Queue $queue ) {
		$this->instances[ $queue->get_name() ] = $queue;
	}

	public function queues() {
		return $this->instances;
	}

	public function get( $queue_name ) {
		if ( isset( $this->instances[ $queue_name ] ) ) {
			return $this->instances[ $queue_name ];
		}

		throw new \Exception( __( 'Queue does not exist', 'tribe' ) );
	}

	public function remove( $queue_name ) {
		if ( isset( $this->instances[ $queue_name ] ) ) {
			unset ( $this->instances[ $queue_name ] );
		}
	}

}