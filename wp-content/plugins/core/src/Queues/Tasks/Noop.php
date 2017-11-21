<?php

namespace Tribe\Project\Queues\Tasks;

use Tribe\Project\Queues\Contracts\Task;

class Noop implements Task {
	public function handle( array $args ) {
		$success = rand( 0, 10 );
		if ( $success ) {
			\WP_CLI::line( 'Fake task ' . $args['fake'] .  ' processed' );
			return true;
		}

		\WP_CLI::line( 'Fake task failed, releasing ack' );
		return false;

	}
}