<?php

namespace Tribe\Project\CLI\Queues;

use Tribe\Project\CLI\Command;
use Tribe\Project\Queues\Queue_Collection;

class Cleanup extends Command {

	/**
	 * @var Queue_Collection
	 */
	protected $queues;

	public function __construct( Queue_Collection $queue_collection ) {
		$this->queues = $queue_collection;
		parent::__construct();
	}

	public function command() {
		return 'queues cleanup';
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

	public function description() {
		return __( 'Runs the cleanup command for a given queue.', 'tribe' );
	}

	public function run_command( $args, $assoc_args ) {
		if ( ! isset( $args[0] ) ) {
			\WP_CLI::error( __( 'You must specify which queue you wish to process.', 'tribe' ) );
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

		$queue->cleanup();
	}

}