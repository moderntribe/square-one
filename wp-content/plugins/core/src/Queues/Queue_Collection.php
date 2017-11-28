<?php

namespace Tribe\Project\Queues;

use Tribe\Project\Queues\Contracts\Queue;

class Queue_Collection {

	protected static $instances = [];

	public function __construct( Queue $queue ) {
		self::$instances[ $queue->get_name() ] = $queue;
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

}