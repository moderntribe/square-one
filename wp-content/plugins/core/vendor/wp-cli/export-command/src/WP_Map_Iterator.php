<?php

class WP_Map_Iterator extends IteratorIterator {
	function __construct( $iterator, $callback ) {
		$this->callback = $callback;
		parent::__construct( $iterator );
	}

	function current() {
		$original_current = parent::current();
		return call_user_func( $this->callback, $original_current );
	}
}
