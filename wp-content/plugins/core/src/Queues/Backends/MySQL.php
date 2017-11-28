<?php

namespace Tribe\Project\Queues\Backends;

use Tribe\Project\Queues\Contracts\Backend;
use Tribe\Project\Queues\Message;

class MySQL implements Backend {

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
			'args'         => json_encode( $message->get_args() ),
			'priority'     => $message->get_priority(),
			'taken'        => 0,
			'done'         => 0,
		];
	}

	public function dequeue( string $queue_name ) {
		global $wpdb;

		$queue = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM $this->table_name
				WHERE queue = %s
				AND taken = 0 
				AND done = 0
				ORDER BY priority ASC
				LIMIT 0,1
				",
				$queue_name
			),
			'ARRAY_A'
		);

		if ( empty( $queue ) ) {
			return;
		}

		$queue['args'] = json_decode( $queue['args'], 1 );

		$wpdb->update(
			$this->table_name,
			[ 'taken' => time() ],
			[ 'id' => $queue['id'] ]
		);

		return new Message( $queue['task_handler'], $queue['args'], $queue['priority'], $queue['id'] );

	}

	public function ack( string $job_id, string $queue_name ) {
		global $wpdb;

		$wpdb->update(
			$this->table_name,
			[ 'done' => time() ],
			[ 'id' => $job_id ]
		);
	}

	public function nack( string $job_id, string $queue_name ) {
		global $wpdb;

		$wpdb->update(
			$this->table_name,
			[ 'taken' => 0 ],
			[ 'id' => $job_id ]
		);
	}

	public function cleanup() {
		global $wpdb;

		$stale = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT id FROM $this->table_name
				WHERE taken < %d
				",
				time() - 300
			)
		);

		foreach ( $stale as $id ) {
			$this->nack( $id, 'null' );
		}
	}

	public function get_type(): string {
		return self::class;
	}

	public function count( string $queue_name ): int {
		global $wpdb;

		return $wpdb->get_var( $wpdb->prepare (
			"SELECT COUNT(*) FROM $this->table_name WHERE queue = %s AND done = 0",
			$queue_name
		) );

	}

	public function table_exists() {
		global $wpdb;

		$table_exists = $wpdb->query( $wpdb->prepare(
			'SHOW TABLES LIKE %s',
			$wpdb->prefix . MySQL::DB_TABLE
		) );

		return $table_exists ?: false;
	}

	public function create_table() {
		global $wpdb;

		$table_name = $wpdb->prefix . MySQL::DB_TABLE;
		$wpdb->query(
			"CREATE TABLE $table_name (
					id bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
					queue varchar(255) NOT NULL,
					task_handler varchar(255) NOT NULL,
					args text NOT NULL,
					priority int(3),
					taken int(10) NOT NULL DEFAULT 0,
					done int(10)
				)"
		);
	}
}
