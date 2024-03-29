<?php declare(strict_types=1);

/**
 * ATTENTION! This is not an ordinary WP pluggable function replacement.
 * Instead of sending a message we are adding the message to our queue system.
 */

use Tribe\Libs\Queues\Queue_Collection;
use Tribe\Libs\Queues\Tasks\Email;

if ( ! function_exists( 'wp_mail' ) &&
     ! ( defined( 'WP_CLI' ) && WP_CLI ) && // phpcs:ignore -- phpcs doesn't handle spaces used for alignment
	 ( defined( 'QUEUE_MAIL' ) && QUEUE_MAIL ) ) {

	/**
	 * @param string|string[] $to
	 * @param string          $subject
	 * @param string          $message
	 * @param string|string[] $headers
	 * @param string|string[] $attachments
	 *
	 * @throws \Exception
	 * @throws \Psr\Container\ContainerExceptionInterface
	 * @throws \Psr\Container\NotFoundExceptionInterface
	 */
	function wp_mail( $to, string $subject, string $message, $headers = '', $attachments = [] ): void {
		$args = [
			'to'          => $to,
			'subject'     => $subject,
			'message'     => $message,
			'headers'     => $headers,
			'attachments' => $attachments,
		];

		$queue_name = defined( 'QUEUE_MAIL_QUEUE_NAME' ) ? QUEUE_MAIL_QUEUE_NAME : 'default';
		$collection = tribe_project()->container()->get( Queue_Collection::class );

		try {
			$queue = $collection->get( $queue_name );
		} catch ( Throwable $e ) {
			throw new Exception( __( 'The queue_name specified does not exist. This email message was not queued for send', 'tribe' ) );
		}

		$queue->dispatch( Email::class, $args );
	}
}
