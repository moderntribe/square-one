<?php

namespace Tribe\Project\CLI\Queues;

use Tribe\Project\CLI\Command;
use Tribe\Project\Queues\Queue_Collection;
use Tribe\Project\Queues\Tasks\Noop;

class Add_Tasks extends Command {

	/**
	 * @var Queue_Collection
	 */
	protected $queues;

	public function __construct( Queue_Collection $queue_collection ) {
		$this->queues = $queue_collection;
		parent::__construct();
	}

	public function command() {
		return 'queues add-tasks';
	}

	public function description() {
		return __( 'Add mock tasks to verify queue functionality', 'tribe' );
	}

	public function arguments() {
		return [
			[
				'type'        => 'positional',
				'name'        => 'queue',
				'optional'    => false,
				'description' => __( 'The name of the Queue.', 'tribe' ),
			],
		];
	}

	public function run_command( $args, $assoc_args ) {
		if ( ! isset( $assoc_args['count'] ) ) {
			$assoc_args['count'] = rand( 1, 50 );
		}

		$queue_name = $args[0];

		if ( ! array_key_exists( $queue_name, $this->queues->queues() ) ) {
			\WP_CLI::error( __( 'That queue name doesn\'t appear to be valid.', 'tribe' ) );
		}

		try {
			$queue = $this->queues->get( $queue_name );
		} catch ( \Exception $e ) {
			\WP_CLI::error( $e->getMessage() );
		}

		for ( $i = 1; $i < $assoc_args['count']; $i ++ ) {
			$queue->dispatch( Noop::class, [ 'noop' => 'task' . microtime() ], $i );
		}
	}

}