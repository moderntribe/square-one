<?php

namespace Tribe\Project\CLI;

use Tribe\Project\Queues\Contracts\Queue;

class Queues extends \WP_CLI_Command {

	/**
	 * ## EXAMPLES
	 *
	 *     wp queues list
	 *
	 * @when after_wp_load
	 */
	public function list() {
		$queues = [];
		foreach ( Queue::instances() as $queue ) {
			/** var Tribe\Project\Queues\Contracts\Queue $queue */
			$queues[]=[
				'Queue' => $queue->get_name(),
				'Backend' => $queue->get_backend_type()
			];
		}

		\WP_CLI::table( $queues );
	}

}