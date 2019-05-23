<?php

namespace Tribe\Tests\Queues;

use Tribe\Project\Queues\Contracts\Task;

class Test_Task implements Task {
	/**
	 * @param array $args
	 *
	 * @throws \Exception
	 * @return bool
	 */
	public function handle( array $args ): bool
	{
		if (isset($args['sleep']) && is_int($args['sleep'])) {
			sleep($args['sleep']);
		}

		if (isset($args['return'])) {
			switch ($args['return']) {
				case 'true':
					return true;
				case 'false':
					return false;
				case 'exception':
					throw new \Exception('Task handler threw!');
			}
		}

		/** We shouldn't get here. */
		throw new \Exception('$args["return"] must be either "true", "false" or "exception".');
	}
}