<?php

namespace Tribe\Project\Queues;

use Tribe\Project\Queues\Contracts\Queue;

class DefaultQueue extends Queue {

	const NAME = 'default';

	public function get_name(): string {
		return self::NAME;
	}

}
