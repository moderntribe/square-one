<?php

abstract class Base_Webdriver_Cest {
	public function _before( WebDriverTester $I ) {
		// Webdriver must be on some page to be able to set cookies
		$I->amOnPage( '/' );
		$I->setCookie( "TRIBE_LOAD_TESTS_CONFIG", "TRUE" );

		if ( $I->shouldUseXdebug() ) {
			$I->setCookie( "XDEBUG_SESSION", "PHPSTORM" );
		}
	}
}
