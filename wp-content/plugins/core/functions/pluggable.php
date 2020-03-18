<?php

/**
 * ATTENTION! This is not an ordinary WP pluggable function replacement.
 * Instead of sending a message we are adding the message to our queue system.
 */
if ( ! function_exists( 'wp_mail' ) &&
	 ! ( defined( 'WP_CLI' ) && WP_CLI ) &&
	 ( defined( 'QUEUE_MAIL' ) && QUEUE_MAIL ) ) {
	function wp_mail( $to, $subject, $message, $headers = '', $attachments = [] ) {
		$args = [
			'to'          => $to,
			'subject'     => $subject,
			'message'     => $message,
			'headers'     => $headers,
			'attachments' => $attachments,
		];

		$queue_name = defined( 'QUEUE_MAIL_QUEUE_NAME' ) ? QUEUE_MAIL_QUEUE_NAME : 'default';
		$collection = new \Tribe\Project\Queues\Queue_Collection();

		try {
			$queue = $collection->get( $queue_name );
		} catch ( \Exception $e ) {
			throw new \Exception( __( 'The queue_name specified does not exist. This email message was not queued for send', 'tribe' ) );
		}

		$queue->dispatch( \Tribe\Project\Queues\Tasks\Email::class, $args );
	}
}
