<?php

namespace Tribe\Project\Queues\Tasks;

use Tribe\Project\Queues\Contracts\Task;

class Email implements Task {

	public function handle( array $args ) {
		return wp_mail( $args['to'], $args['subject'], $args['message'], $args['headers'], $args['attachments'] );
	}

	public static function mail( $args, $queue_name = 'default' ) {
		if( $queue = Queue::get_instance( $queue_name ) ) {
			$queue->dispatch( self::class, $args );
		}
	}
}