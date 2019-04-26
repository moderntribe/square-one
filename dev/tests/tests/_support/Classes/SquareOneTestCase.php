<?php

namespace Tribe\Tests;

use Codeception\TestCase\WPTestCase;

/**
 * Class SquareOneTestCase
 * Test case with specific actions for Square One projects.
 *
 * @package Tribe\Tests
 */
class SquareOneTestCase extends WPTestCase {
	protected $original_providers;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$this->save_original_pimple_container();
	}

	public function tearDown() {
		// your tear down methods here
		$this->reset_pimple_container();

		// then
		parent::tearDown();
	}

	/**
	 * Saves a copy of the original Pimple container collection before tests begin.
	 */
	private function save_original_pimple_container() {
		if ( $this->original_providers === null ) {
			$this->original_providers = tribe_project()->container()->keys();
		}
	}

	/**
	 * Resets the Pimple container after each test.
	 */
	protected function reset_pimple_container() {
		$providers_after_test = tribe_project()->container()->keys();
		$new_providers        = array_diff( $providers_after_test, $this->original_providers );
		foreach ( $new_providers as $p ) {
			tribe_project()->container()->offsetUnset( $p );
		}
	}
}