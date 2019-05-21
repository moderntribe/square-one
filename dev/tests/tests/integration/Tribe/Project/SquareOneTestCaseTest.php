<?php

namespace Tribe\Project;

use Tribe\Tests\SquareOneTestCase;

class SquareOneTestCase_Test extends SquareOneTestCase {
	/** @test */
	public function should_keep_original_providers() {
		$original_providers = tribe_project()->container()->keys();
		$this->reset_pimple_container();
		$providers_after_reset = tribe_project()->container()->keys();

		$this->assertEquals( $original_providers, $providers_after_reset );
	}

	/** @test */
	public function should_remove_new_providers() {
		// Setup
		$original_providers = tribe_project()->container()->keys();
		$new_providers_slug = [ 'square_one_test_case_foo', 'square_one_test_case_bar' ];
		foreach ( $new_providers_slug as $slug ) {
			tribe_project()->container()[ $slug ] = function(){};
		}
		$providers_after_insertion = tribe_project()->container()->keys();

		// Execute
		$this->reset_pimple_container();
		$providers_after_reset = tribe_project()->container()->keys();

		// Assert
		foreach ( $new_providers_slug as $slug ) {
			$this->assertNotContains( $slug, $original_providers );
			$this->assertContains( $slug, $providers_after_insertion );
			$this->assertNotContains( $slug, $providers_after_reset );
		}
		$this->assertEquals( $original_providers, $providers_after_reset );
	}
}