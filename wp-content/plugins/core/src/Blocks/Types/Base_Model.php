<?php

namespace Tribe\Project\Blocks\Types;

class Base_Model {
	protected $mode;
	protected $data;
	protected $name;

	/**
	 * Base_Controller constructor.
	 *
	 * @param $block
	 */
	public function __construct( $block ) {
		$this->mode = $block[ 'mode' ] ?? 'preview';
		$this->data = $block[ 'data' ] ?? [];
		$this->name = $block[ 'name' ] ? str_replace( 'acf/', '', $block[ 'name' ] ) : '';
	}

	/**
	 * @param $key
	 *
	 * @return bool|mixed
	 */
	public function get( $key, $default = false ) {
		$value = get_field( $key );
		//check to support nullable type properties in components.
		// ACF will in some cases return and empty string when we may want it to be null.
		// This allows us to always determine the default.
		return ! empty( $value )
			? $value
			: $default;
	}
}
