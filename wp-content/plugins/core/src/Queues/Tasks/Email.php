<?php

namespace Tribe\Project\Queues\Tasks;

use Tribe\Project\Queues\Contracts\Task;

class Email implements Task {

	public function handle( array $args ) {
		wp_mail( ... );
	}

	public static function mail( $args, $queue = 'default' ) {

	}
}