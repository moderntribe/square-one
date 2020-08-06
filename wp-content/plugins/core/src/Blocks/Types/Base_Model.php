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
		$real_key = $this->mode === 'preview' ? 'field_' . $this->name . '_' . $key : $key;

		return $this->data[ $real_key ] ?? false;
	}
}