<?php

namespace Tribe\Project\Request;

class Server {

	protected $headers;

	/**
	 * Get all request headers, manually if not on Apache.
	 *
	 * @return array
	 */
	public function get_headers(): array {
		if ( function_exists( 'getallheaders' ) ) {
			return getallheaders();
		}

		if ( ! is_array( $_SERVER ) ) {
			return [];
		}

		$headers = [];

		foreach ( $_SERVER as $name => $value ) {
			if ( substr( $name, 0, 5 ) == 'HTTP_' ) {
				$headers[ str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5 ) ) ) ) ) ] = $value;
			}
		}

		$this->headers = $headers;

		return $this->headers;
	}

	/**
	 * Get all of the input values depending on method.
	 *
	 * @return array
	 */
	public function get_input(): array {
		if ( $this->has_body() ) {
			return $this->get_json_input();
		}

		switch ( $this->get_method() ) {
			case 'GET':
				return $_GET;
			case 'POST':
				return $_POST;
			default:
				return $_GET;
		}
	}

	/**
	 * Check if the request contains body content.
	 *
	 * @return bool
	 */
	public function has_body(): bool {
		return ! empty( file_get_contents( 'php://input' ) );
	}

	/**
	 * Return request Method type.
	 *
	 * @return null|string
	 */
	public function get_method() {
		$method = $_SERVER['REQUEST_METHOD'];

		if ( 'POST' === $method && $method = $this->get_header( 'X-HTTP-METHOD-OVERRIDE' ) ) {
			$method = strtoupper( $method );
		}

		return $method;
	}

	/**
	 * Get the JSON values from the request body.
	 *
	 * @return array
	 */
	public function get_json_input(): array {
		$json_content = file_get_contents( 'php://input' );
		return json_decode( $json_content, true );
	}

	/**
	 * Assemble the current URL from the Request.
	 *
	 * @param bool $use_forwarded_host
	 *
	 * @return string
	 */
	public function get_url( $use_forwarded_host = false ) {
		$s        = $_SERVER;
		$ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
		$sp       = strtolower( $s['SERVER_PROTOCOL'] );
		$protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
		$port     = $s['SERVER_PORT'];
		$port     = ( ( ! $ssl && $port == '80' ) || ( $ssl && $port == '443' ) ) ? '' : ':' . $port;
		$host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
		$host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
		return $protocol . '://' . $host;
	}

	/**
	 * Get any query parameters from the request.
	 *
	 * @return array
	 */
	public function get_query_params() {
		return $_GET ?? [];
	}

	/**
	 * Return the current instance of $wp_query. Gets the most-up-to-date global each time as it may change depending
	 * on when it is referenced.
	 *
	 * @return \WP_Query
	 */
	public function get_query(): \WP_Query {
		global $wp_query;

		return $wp_query;
	}

	/**
	 * Get the current path from the request. Optionally include query parameters.
	 *
	 * @param bool $include_params
	 *
	 * @return string
	 */
	public function get_path( $include_params = false ) {
		$path = trim( $_SERVER['REQUEST_URI'] ?? '', '/' );

		return $include_params ? $path : strtok( $path, '?' );
	}

	/**
	 * Get the header value by the specified key.
	 *
	 * @param $key
	 *
	 * @return null
	 */
	public function get_header( $key ) {
		return $this->headers[ $key ] ?? null;
	}
}
