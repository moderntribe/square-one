<?php

namespace Tribe\Tests\Queues;

use Tribe\Project\Queues\Contracts\Queue;

class Test_Queue extends Queue {

	const NAME = 'test_queue';

	public function get_name(): string {
		return self::NAME;
	}
}