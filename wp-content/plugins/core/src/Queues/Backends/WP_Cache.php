<?php

namespace Tribe\Project\Queues\Backends;

use Tribe\Project\Queues\Contracts\Backend;
use Tribe\Project\Queues\Message;

/**
 * This is just a simple implementation to make the Queue framework easy to test
 * and easy to run on local / dev. You shouldn't use this Backend on production
 * as it's suceptible to race conditions. You *WILL* lose tasks in any moderately
 * active website.
 */
class WP_Cache implements Backend {

	public function enqueue( string $queue_name, Message $m ) {

		$queue = $this->get_queue( $queue_name );

		$queue[ uniqid( 'queue', true ) ] = [
			'priority'     => $m->get_priority(),
			'task_handler' => $m->get_task_handler(),
			'args'         => $m->get_args(),
			'taken'        => false,
		];

		// Sort by priority
		usort( $queue, function ( $first, $second ) {
			return $first['priority'] <=> $second['priority'];
		} );

		$this->save_queue( $queue_name, $queue );
	}

	public function dequeue( string $queue_name ): Message {
		$queue = $this->get_queue( $queue_name );

		if ( empty( $queue ) ) {
			throw new \RuntimeException( 'No messages available to reserve.' );
		}

		$last_key = end( array_keys( $queue ) );
		reset( $queue );
		$job_id = key( $queue );
		$taken  = $queue[ $job_id ]['taken'];

		// Traverse the queue and look for non taken jobs
		while ( $taken !== false && $last_key !== $job_id ) {
			next( $queue );
			$job_id = key( $queue );
			$taken  = $queue[ $job_id ]['taken'];
		}

		// Every item in the queue is taken. You probably need more consumers.
		if ( $taken !== false ) {
			throw new \RuntimeException( 'All messages have been reserved.' );
		}

		$queue[ $job_id ]['taken'] = time();

		$this->save_queue( $queue_name, $queue );

		return new Message( $queue[ $job_id ]['task_handler'], $queue[ $job_id ]['args'], $queue[ $job_id ]['priority'], $job_id );

	}

	public function ack( string $job_id, string $queue_name ) {
		$queue = $this->get_queue( $queue_name );

		if ( empty( $queue[ $job_id ] ) ) {
			return;
		}

		$data = $queue[ $job_id ];
		unset( $queue[ $job_id ] );
		$this->save_queue( $queue_name, $queue );

		$data['done']   = time();
		$data['queue']  = $queue_name;
		$data['job_id'] = $job_id;
		$this->log( $data );
	}

	public function nack( string $job_id, string $queue_name ) {
		$queue = $this->get_queue( $queue_name );

		if ( empty( $queue[ $job_id ] ) ) {
			return;
		}

		$queue[ $job_id ]['taken'] = false;

		$this->save_queue( $queue_name, $queue );
	}

	protected function get_queue( string $queue_name ): array {
		$queue = wp_cache_get( 'queue.new.' . $queue_name );

		if ( $queue === false ) {
			$queue = [];
		} else {
			$queue = json_decode( $queue, true );
		}

		return $queue;
	}

	protected function save_queue( string $queue_name, array $queue ) {
		wp_cache_set( 'queue.new.' . $queue_name, json_encode( $queue ) );
	}

	protected function log( array $data ) {
		$queue = wp_cache_get( 'queue.log' );

		if ( $queue === false ) {
			$queue = [];
		} else {
			$queue = json_decode( $queue );
		}

		$queue[] = $data;

		wp_cache_set( 'queue.log', json_encode( $queue ) );
	}

	public function get_type(): string {
		return self::class;
	}

	public function count( string $queue_name ): int {
		$queue = $this->get_queue( $queue_name );

		return \count( $queue );
	}

	public function cleanup() {
		null;
	}
}
