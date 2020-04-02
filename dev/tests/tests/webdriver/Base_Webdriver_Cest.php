<?php

abstract class Base_Webdriver_Cest {
	public function _before( WebDriverTester $I ) {
		if ( $I->shouldUseXdebug() ) {
			// Webdriver must be on some page to be able to set cookies
			$I->amOnPage( '/' );
			$I->setCookie( "XDEBUG_SESSION", "PHPSTORM" );
		}
	}
}
