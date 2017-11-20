<?php

namespace Tribe\Project\Queues\Tasks;

use Tribe\Project\Queues\Contracts\Task;

class Null_Task implements Task {
	public function handle( array $args ) {
		return true;
	}
}