<?php

namespace Tribe\Project\Queues\Contracts;

interface Task {

	public function handle( array $args ) : bool;
}
