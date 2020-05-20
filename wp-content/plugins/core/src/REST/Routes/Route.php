<?php
/**
 * The abstract route class.
 */
declare( strict_types=1 );

namespace Tribe\Project\REST\Routes;

use Tribe\Project\REST_API\Models\Error_Response_Object;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Class Route.
 */
abstract class Route extends WP_REST_Controller {

	// Argument keys.
	public const REQ = 'required';

	// Request params.
	public const ID       = 'ID';

	// Response keys.
	public const STATUS  = 'status';
	public const MESSAGE = 'message';

	// Response statuses.
	public const ERROR           = 'error';
	public const INVALID_REQUEST = 'invalid-request';
	public const SUCCESS         = 'success';
	public const USER_NOT_FOUND  = 'user-not-found';

	/**
	 * API version 1.
	 *
	 * @var string Version
	 */
	protected $version = 'v1';

	/**
	 * @var string Namespace.
	 */
	protected $namespace_base = 'square-one/';

	/**
	 * Route constructor.
	 */
	public function __construct() {
		$this->namespace = $this->namespace_base . $this->version;
	}

	/**
	 * Validate a request argument based on details registered to the route.
	 *
	 * @param mixed           $value   Value of the argument.
	 * @param WP_REST_Request $request The current request object.
	 * @param string          $param   Key of the parameter. In this case it is 'filter'.
	 *
	 * @return WP_Error|boolean
	 */
	public function validate( $value, WP_REST_Request $request, string $param ) {
		switch ( $param ) {
			case self::ID:
				return is_numeric( $value );
		}

		return false;
	}

	/**
	 * Sanitizes our parameters.
	 *
	 * @param mixed           $value   Value of the argument.
	 * @param WP_REST_Request $request The current request object.
	 * @param string          $param   Key of the parameter. In this case it is 'filter'.
	 *
	 * @return mixed|WP_Error
	 */
	public function sanitize( $value, WP_REST_Request $request, string $param ) {
		switch ( $param ) {

			case self::ID:
				return absint( $value );
		}

		return new WP_Error( 'rest_invalid_param', sprintf( '%s was invalid.', esc_html( $param ) ), [ 'status' => 400 ] );
	}

	/**
	 * Returns an error object.
	 *
	 * @param string $status  The status code.
	 * @param string $message The status message.
	 * @param int    $code    HTTP response code.
	 *
	 * @return WP_REST_Response
	 */
	protected function error( string $status, string $message, int $code ): WP_REST_Response {
		$error = new Error_Response_Object( $status, $message );

		return new WP_REST_Response( $error, $code );
	}

	/**
	 * Outputs the default args.
	 *
	 * @param bool  $required Whether or not the arg is required.
	 * @param array $args     Optional arguments and overrides.
	 *
	 * @return array
	 */
	protected function get_args( bool $required = false, array $args = [] ): array {
		if ( $required ) {
			$args[ self::REQ ] = true;
		}

		return array_merge( [
			'validate_callback' => [ $this, 'validate' ],
			'sanitize_callback' => [ $this, 'sanitize' ],
		], $args );
	}
}
