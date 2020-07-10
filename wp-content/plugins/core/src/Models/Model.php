<?php

namespace Tribe\Project\Models;

/**
 * Class Model
 *
 * @package Tribe\Project\Models
 */
class Model implements Model_Interface {

	/**
	 * @param $key
	 *
	 * @return bool
	 */
	public function __get( $key ) {
		if ( property_exists( $this, $key ) ) {
			return $this->$key;
		}

		return false;
	}

	/**
	 * @param $key
	 *
	 * @return bool
	 */
	public function __isset( $key ) {
		if ( isset( $this->$key ) && ! method_exists( $this, $key ) ) {
			return true;
		}

		return false;
	}

}
