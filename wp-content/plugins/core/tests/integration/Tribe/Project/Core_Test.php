<?php

namespace Tribe\Project;

use Codeception\TestCase\WPTestCase;

class Core_Test extends WPTestCase {
	public function test_we_set_up_the_test_suite_correctly() {
		$this->assertTrue( true );
		$plugins = wp_get_active_and_valid_plugins();
		$this->assertTrue( in_array( WP_PLUGIN_DIR . '/core/core.php', $plugins ) );
	}
}