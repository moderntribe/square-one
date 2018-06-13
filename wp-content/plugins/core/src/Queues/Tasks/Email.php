<?php

namespace Tribe\Project\Queues\Tasks;

use Tribe\Project\Queues\Contracts\Task;

class Email implements Task {

	public function handle( array $args ) : bool {
		return wp_mail( $args['to'], $args['subject'], $args['message'], $args['headers'], $args['attachments'] );
	}

}
