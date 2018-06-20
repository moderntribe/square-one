<?php

namespace Tribe\Project\Queues\Tasks;

use Tribe\Project\Queues\Contracts\Task;

class Email implements Task {

	/**
	 * The wp_mail() function is pluggable and not all third parties respect the core signature that
	 * returns a boolean. So we exception handle to ensure an expected return.
	 *
	 * @param array $args
	 *
	 * @return bool
	 */
	public function handle( array $args ) : bool {
		try {
			wp_mail( $args['to'], $args['subject'], $args['message'], $args['headers'], $args['attachments'] );
		} catch ( \Exception $e ) {
			return false;
		}

		return true;
	}

}
