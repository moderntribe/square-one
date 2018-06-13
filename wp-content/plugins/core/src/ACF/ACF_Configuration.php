<?php


namespace Tribe\Project\ACF;


abstract class ACF_Configuration {
	protected $key        = '';
	protected $attributes = [ ];
	protected $key_prefix = '';

	/**
	 * ACF_Configuration constructor.
	 *
	 * @param string $key The unique identifier for this item. Will be
	 *                    automatically prefixed with "group_" or "field_".
	 */
	public function __construct( $key ) {
		$this->key = $key;
		$this->attributes[ 'key' ] = $this->key_prefix . '_' . $this->key;
	}

	/**
	 * Set a single attribute
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @return void
	 */
	public function set( $key, $value ) {
		if ( $key == 'key' ) {
			throw new \InvalidArgumentException( 'Key cannot be changed' );
		}
		$this->attributes[ $key ] = $value;
	}

	/**
	 * Set an array of attributes
	 *
	 * @param array $attributes
	 * @return void
	 */
	public function set_attributes( array $attributes ) {
		foreach ( $attributes as $key => $value ) {
			$this->set( $key, $value );
		}
	}

	/**
	 * Get all the attributes, formatted as
	 * an ACF-ready array
	 *
	 * @return array
	 */
	public function get_attributes() {
		return $this->attributes;
	}

	/**
	 * Get the value of a single attribute
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get( $key ) {
		$attributes = $this->get_attributes();
		if ( isset( $attributes[ $key ] ) ) {
			return $attributes[ $key ];
		}
		return null;
	}
}