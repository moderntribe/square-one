<?php

namespace Tribe\Project;

use Tribe\Tests\Test_Case;

class Core_Test extends Test_Case {
	public function test_we_set_up_the_test_suite_correctly() {
		$this->assertTrue( true );
		$this->assertTrue( is_plugin_active( 'core/core.php' ) );
	}
}