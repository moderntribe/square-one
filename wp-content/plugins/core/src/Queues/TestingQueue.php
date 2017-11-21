<?php

namespace Tribe\Project\Queues;

use Tribe\Project\Queues\Contracts\Queue;

class TestingQueue extends Queue {

	const NAME = 'testing';

	public function get_name(): string {
		return self::NAME;
	}
}
