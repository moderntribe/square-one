<?php

namespace Tribe\Project\Models;

class Model implements Model_Interface {

	public function __get( $key ) {
		if ( property_exists( $this, $key ) ) {
			return $this->$key;
		}

		return false;
	}

	public function __isset( $key ) {
		if ( isset( $this->$key ) && ! method_exists( $this, $key ) ) {
			return true;
		}

		return false;
	}

}
