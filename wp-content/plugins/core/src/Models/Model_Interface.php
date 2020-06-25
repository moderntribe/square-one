<?php

namespace Tribe\Project\Models;

interface Model_Interface {
	public function __get( $key );
	public function __isset( $key );
}
