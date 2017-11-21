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
		global $wpdb;

		$data = $this->prepare_data( $message );
		$data['queue'] = $queue_name;

		return $wpdb->insert( $this->table_name, $data );
	}

	private function prepare_data( $message ) {
		return [
			'task_handler' => $message->get_task_handler(),
			'args'         => $message->get_args(),
			'priority'     => $message->get_priority(),
		];
	}

	public function dequeue( string $queue_name ) {

	}

	public function ack( string $job_id, string $queue_name ) {

	}

	public function nack( string $job_id, string $queue_name ) {

	}

	public function get_type(): string {
		return self::class;
	}

	public function count( string $queue_name ): int {
		global $wpdb;

		return $wpdb->query( $wpdb->prepare(
			'SELECT COUNT(id) FROM %s',
			$this->table_name
		) );
	}
}
