<?php

namespace Tribe\Project\API\Controllers;

/**
 * Class Controller
 *
 * @package Tribe\API\Controllers
 */
abstract class Controller implements Servable {

	// Statuses.
	public const SUCCESS = 'success';
	public const FAIL    = 'fail';

	// Response keys.
	public const STATUS = 'status';
	public const DATA   = 'data';
	public const MSG    = 'message';

	/**
	 * @inheritDoc
	 */
	abstract public function handle_request( array $args = [] );

	/**
	 * Echoes the data with a success message.
	 *
	 * @param mixed $data The data to echo.
	 */
	protected function success( $data ): void {
		http_response_code( 200 );

		echo json_encode( [
			self::STATUS => self::SUCCESS,
			self::DATA   => $data,
		] );

		exit;
	}

	/**
	 * Echoes a failure response.
	 *
	 * @param string $message A human-readable message.
	 * @param int    $code    The HTTP status code.
	 */
	protected function fail( $message, $code = 500 ): void {
		http_response_code( $code );

		echo json_encode( [
			self::STATUS => self::FAIL,
			self::MSG    => $message,
		] );

		exit;
	}
}
