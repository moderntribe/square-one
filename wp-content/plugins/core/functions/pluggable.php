<?php

/**
 * ATTENTION! This is not an ordinary WP pluggable function replacement.
 * Instead of sending a message we are adding the message to our queue system.
 */
if ( ! function_exists( 'wp_mail' ) &&
     ! ( defined( 'WP_CLI' ) && WP_CLI ) &&
     ( defined( 'QUEUE_MAIL' ) && QUEUE_MAIL ) ) {
	function wp_mail( $to, $subject, $message, $headers = '', $attachments = array() ) {
		$args = [
			'to'          => $to,
			'subject'     => $subject,
			'message'     => $message,
			'headers'     => $headers,
			'attachments' => $attachments,
		];

		if ( defined( 'QUEUE_MAIL_QUEUE_NAME') ) {
			$queue_name = QUEUE_MAIL_QUEUE_NAME;
		} else {
			$queue_name = 'default';
		}

		if( $queue = \Tribe\Project\Queues\Contracts\Queue::get_instance( $queue_name ) ) {
			$queue->dispatch( \Tribe\Project\Queues\Tasks\Email::class, $args );
		}

	}
}