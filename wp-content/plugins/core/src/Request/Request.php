<?php

namespace Tribe\Project\Request;

class Request {

	/**
	 * @var array
	 */
	protected $headers;

	/**
	 * @var array
	 */
	protected $input;

	/**
	 * @var string
	 */
	protected $method;

	/**
	 * @var array
	 */
	protected $query_params;

	/**
	 * @var Server
	 */
	protected $server;

	/**
	 * Request constructor.
	 *
	 * @param Server $server
	 */
	public function __construct( Server $server ) {
		$this->server = $server;
		$this->fill_values_from_request();
	}

	/**
	 * Fill the values with data from the request.
	 */
	protected function fill_values_from_request() {
		$this->headers      = $this->server->get_headers();
		$this->input        = $this->server->get_input();
		$this->query_params = $this->server->get_query_params();
		$this->url          = $this->server->get_url();
	}

	/**
	 * Return the current instance of $wp_query. Gets the most-up-to-date global each time as it may change depending
	 * on when it is referenced.
	 *
	 * @return \WP_Query
	 */
	public function query(): \WP_Query {
		return $this->server->get_query();
	}

	/**
	 * Get all headers.
	 *
	 * @return array
	 */
	public function headers() {
		return $this->headers;
	}

	/**
	 * Get a specific header value by the provided key.
	 *
	 * @param $key
	 *
	 * @return string|null
	 */
	public function header( $key ) {
		return $this->headers[ $key ] ?? null;
	}

	/**
	 * Retrieve a specific input value (method agnostic) by key.
	 *
	 * @param     $key
	 * @param int $filter
	 *
	 * @return mixed
	 */
	public function input( $key, $filter = FILTER_DEFAULT ) {
		$value = $this->input[ $key ] ?? null;
		return filter_var( $value, $filter );
	}

	/**
	 * Return all input values. If not a GET method, return all inputs and any query parameters.
	 *
	 * @return array
	 */
	public function all() {
		return 'GET' === $this->server->get_method() ? $this->input : $this->input + $this->query_params;
	}

	/**
	 * Return only the provided input values by the array of keys.
	 *
	 * @param array $keys
	 *
	 * @return array
	 */
	public function only( $keys ) {
		$keys    = is_array( $keys ) ? $keys : func_get_args();
		$results = [];
		$input   = $this->all();

		foreach ( $keys as $key ) {
			if ( array_key_exists( $key, $input ) ) {
				$results[ $key ] = $input[ $key ];
			}
		}

		return $results;
	}

	/**
	 * Return all input values except the provided keys.
	 *
	 * @param array $keys
	 *
	 * @return array
	 */
	public function except( $keys ) {
		$keys  = is_array( $keys ) ? $keys : func_get_args();
		$input = $this->all();

		foreach ( $keys as $key ) {
			unset( $input[ $key ] );
		}

		return $input;
	}

	/**
	 * Determine if the request has an input value with the provided key.
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function has( $key ): bool {
		$keys = is_array( $key ) ? $key : func_get_args();
		return $this->check_for_keys( $keys );
	}

	/**
	 * Check if the request has an input value for the provided key which is non-empty.
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function filled( $key ): bool {
		$keys = is_array( $key ) ? $key : func_get_args();
		return $this->check_for_keys( $keys, true );
	}

	/**
	 * Get the current URL path - optionally include the query parameters.
	 *
	 * @param bool $include_params
	 *
	 * @return string
	 */
	public function path( $include_params = false ): string {
		return $this->server->get_path( $include_params );
	}

	/**
	 * Get the current URL; does not include path or query parameters.
	 *
	 * @return string
	 */
	public function url(): string {
		return rtrim( preg_replace( '/\?.*/', '', $this->server->get_url() ), '/' );
	}

	/**
	 * Get the full URL including path and any query parameters.
	 *
	 * @return string
	 */
	public function full_url(): string {
		return $this->url() . '/' . $this->path( true );
	}

	/**
	 * Check if the current path matches the provided value or pattern.
	 *
	 * @param string $path
	 *
	 * @return bool
	 */
	public function is( $path ): bool {
		return $this->strings_match( $path, $this->path() );
	}

	/**
	 * Loop through inputs and check if the provided keys exist.
	 *
	 * @param      $keys
	 * @param bool $is_filled
	 *
	 * @return bool
	 */
	protected function check_for_keys( $keys, $is_filled = false ): bool {
		foreach ( $keys as $key ) {
			if ( ! isset( $this->input[ $key ] ) ) {
				return false;
			}

			if ( $is_filled && $this->is_empty_string( $key ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Determine if the given input key is an empty string for "has".
	 *
	 * @param  string $key
	 *
	 * @return bool
	 */
	protected function is_empty_string( $key ) {
		$value = $this->input( $key );

		$boolOrArray = is_bool( $value ) || is_array( $value );

		return ! $boolOrArray && trim( (string) $value ) === '';
	}

	/**
	 * Determine if a value matches the provided pattern.
	 *
	 * @param string $pattern
	 * @param string $value
	 *
	 * @return bool
	 */
	protected function strings_match( $pattern, $value ): bool {
		if ( $pattern === $value ) {
			return true;
		}

		$pattern = preg_quote( $pattern, '#' );

		// Asterisks are translated into zero-or-more regular expression wildcards
		// to make it convenient to check if the strings starts with the given
		// pattern such as "library/*", making any string check convenient.
		$pattern = str_replace( '\*', '.*', $pattern ) . '\z';

		return (bool) preg_match( '#^' . $pattern . '#', $value );
	}
}