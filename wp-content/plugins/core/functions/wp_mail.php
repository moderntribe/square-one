<?php

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


	}
}