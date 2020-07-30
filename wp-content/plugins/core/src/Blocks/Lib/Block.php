<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks\Lib;

class Block {
	/**
	 * @var array
	 */
	protected $attributes;

	/**
	 * Block constructor.
	 *
	 * @param       $name
	 * @param array $attributes
	 */
	public function __construct( $name, $attributes = [] ) {
		$this->attributes           = $attributes;
		$this->attributes[ 'name' ] = $name;
	}

	/**
	 * @param $key
	 * @param $value
	 */
	public function set( $key, $value ) {
		$this->attributes[ $key ] = $value;
	}

	/**
	 * @param array $attributes
	 */
	public function set_attributes( array $attributes ) {
		$this->attributes = $attributes;
	}

	/**
	 * @return array
	 */
	public function get_attributes(): array {
		return $this->attributes;
	}
}