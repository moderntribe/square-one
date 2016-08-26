<?php

namespace Tribe\Project\Settings\Contracts;

/**
 * Class Abstract_Settings
 *
 * @ToDo: Candidate for tribe-libs
 *
 * @package Tribe\Project\Settings
 */
abstract class Base_Settings implements Settings_Builder {

	protected $slug = '';

	public function __construct() {
		$this->set_slug();
	}

	/**
	 * Generates a unique-ish slug for this settings screen
	 */
	protected function set_slug() {
		$this->slug = sanitize_title( $this->get_parent_slug() . '-' . $this->get_title() );
	}

	/**
	 * @param int $priority
	 */
	public function hook( $priority = 10 ) {
		add_action( 'init', [ $this, 'register_settings' ], $priority, 0 );
		add_action( 'init', [ $this, 'register_fields' ], $priority + 1, 0 );
	}

}
