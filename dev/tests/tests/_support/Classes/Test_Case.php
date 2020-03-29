<?php

namespace Tribe\Tests;

use Codeception\TestCase\WPTestCase;

/**
 * Class Test_Case
 * Test case with specific actions for Square One projects.
 *
 * @package Tribe\Tests
 */
class Test_Case extends WPTestCase {
	protected function _before() {
		parent::_before();

		$this->fix_theme_directory_in_integration_tests();
	}

	/**
	 * This basically allows Twig_Template->render() to be
	 * called on integration tests.
	 *
	 * When we are running integration tests, we are using a fresh
	 * database. Since we use "wp" as WordPress root, but "wp-content/themes"
	 * is the actual theme location, when we try to instantiate the Twig_Environment
	 * class, it tries to look for the  template on "/application/www/wp/wp-content/themes/core",
	 * which doesn't exist, so here we just remove the "wp" folder
	 * from the return path.
	 */
	private function fix_theme_directory_in_integration_tests() {
		add_filter( 'template_directory', function ( $template_dir, $template, $theme_root ) {
			return '/application/www/wp-content/themes/core';
		}, 10, 3 );

		add_filter( 'stylesheet_directory', function ( $stylesheet_dir, $stylesheet, $theme_root ) {
			return '/application/www/wp-content/themes/core';
		}, 10, 3 );
	}
}