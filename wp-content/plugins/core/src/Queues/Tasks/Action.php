<?php

namespace Tribe\Project\Queues\Tasks;

class Action implements Task {

	public function handle( array $args ) {
		$action = $args['hook'];
		unset( $args['hook'] );
		do_action_ref_array( $action, $args );
	}
}