<?php

namespace Tribe\Project\Queues\Tasks;

use Tribe\Project\Queues\Contracts\Task;

class Action implements Task {

	public function handle( array $args ) : bool {
		$action = $args['hook'];
		unset( $args['hook'] );
		if ( do_action_ref_array( $action, $args ) ) {
			return true;
		}
		return false;
	}
}