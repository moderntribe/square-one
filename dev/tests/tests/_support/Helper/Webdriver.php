<?php

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Webdriver extends \Codeception\Module {

	public function shouldUseXdebug(): bool {
		$webdriver      = $this->getModule( 'WPWebDriver' );
		$xdebug_enabled = $webdriver->_getConfig( "xdebug_enabled" );

		return (bool) $xdebug_enabled;
	}

}
