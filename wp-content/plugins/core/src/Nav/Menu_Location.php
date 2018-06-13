<?php


namespace Tribe\Project\Nav;


class Menu_Location {
	private $location = '';
	private $description = '';

	public function __construct( $location, $description ) {
		$this->location = $location;
		$this->description = $description;
	}

	public function hook() {
		add_action( 'after_setup_theme', [ $this, 'register' ], 10, 0 );
	}

	public function register() {
		register_nav_menu( $this->location, $this->description );
	}

	public function location() {
		return $this->location;
	}

	public function description() {
		return $this->description;
	}
}