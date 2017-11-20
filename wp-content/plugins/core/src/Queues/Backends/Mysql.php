<?php

namespace Tribe\Project\Queues\Backends;

use Tribe\Project\Queues\Contracts\Backend;
use Tribe\Project\Queues\Message;

class Mysql implements Backend {

	const DB_TABLE = 's1_queue';

	public function __construct() {
		global $wpdb;

		$this->table_name = $wpdb->prefix . self::DB_TABLE;
	}

	public function enqueue( string $queue_name, Message $message ) {

	}

	public function dequeue( string $queue_name ) {

	}

	public function ack( string $job_id, string $queue_name ) {

	}

	public function nack( string $job_id, string $queue_name ) {

	}

	public function get_type(): string {

	}

	public function count( string $queue_name ): int {

	}
}
