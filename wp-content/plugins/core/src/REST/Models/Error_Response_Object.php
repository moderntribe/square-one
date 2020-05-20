<?php
/**
 * Error response object.
 *
 * @package Square1-REST
 */
declare( strict_types=1 );

namespace Tribe\Project\REST_API\Models;

/**
 * Class Error_Response_Object.
 */
class Error_Response_Object {

	/**
	 * @var string
	 */
	public $status;

	/**
	 * @var string
	 */
	public $message;

	public function __construct( string $status, string $message ) {
		$this->status  = $status;
		$this->message = $message;
	}
}
