<?php declare(strict_types=1);

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module\WPWebDriver;
use Codeception\TestInterface;

class Webdriver extends \Codeception\Module {

	private WPWebDriver $webdriver;

	public function _before( TestInterface $test ) {
		parent::_before( $test );

		$this->webdriver = $this->getModule( 'WPWebDriver' );
	}

	public function shouldUseXdebug(): bool {
		$xdebug_enabled = $this->webdriver->_getConfig( "xdebug_enabled" );

		return (bool) $xdebug_enabled;
	}

	public function waitForPageLoad( int $timeout = 10 ) {
		$this->webdriver->waitForJs( 'return document.readyState == "complete"', $timeout );
		$this->webdriver->waitForJqueryAjax( $timeout );
	}

}
