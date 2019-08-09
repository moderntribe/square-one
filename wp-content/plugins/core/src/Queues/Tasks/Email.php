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
			$return = wp_mail( $args['to'], $args['subject'], $args['message'], $args['headers'], $args['attachments'] );
		} catch ( \Exception $e ) {
			// This may need to change on a project basis, depending on what solutions are used for wp_mail(). What kind of Exception
			// are we catching? What does the exception mean? Does it mean it's a failure and don't try again? Does it mean we got bad data?
			// In most cases, we can return false, but a project may need to consider if some other action is necessary due to the exception
			return false;
		}

		return is_bool( $return ) ? $return : true;
	}

}
