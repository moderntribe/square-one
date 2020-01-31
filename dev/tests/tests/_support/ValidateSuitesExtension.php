<?php

use Codeception\Event\SuiteEvent;
use Codeception\Events;
use Codeception\Extension;

class ValidateSuitesExtension extends Extension {

	public static $events = [
		Events::SUITE_BEFORE => 'validateWPDbWithWPLoader',
	];

	/**
	 * @param SuiteEvent $e
	 *
	 * @throws \Codeception\Exception\ModuleRequireException
	 */
	public function validateWPDbWithWPLoader( SuiteEvent $e ) {
		if ( $this->hasModule( 'WPDb' ) && $this->hasModule( 'WPLoader' ) ) {
			$this->validateWPDbBeforeWPLoader( $e );
			$this->validateWPDbWithLoadOnly( $e );
		}
	}

	/**
	 * If both WPDb and WPLoader modules are in use, assert WPDb is loaded first
	 *
	 * @param SuiteEvent $e
	 */
	private function validateWPDbBeforeWPLoader( SuiteEvent $e ) {
		$wpdb_loaded = false;
		foreach ( $this->getCurrentModuleNames() as $module ) {
			if ( $module === 'WPDb' ) {
				$wpdb_loaded = true;
			}
			if ( $module === 'WPLoader' && $wpdb_loaded !== true ) {
				$e->getSuite()->markTestSuiteSkipped(
					sprintf(
						'WPDb module must be loaded before WPLoader in %s. Example: %s',
						$e->getSuite()->getBaseName(),
						<<<EOF

modules:
	- enabled:
		- WPDb # BEFORE the WPLoader one!
		- WPLoader # AFTER the WPDb one!
EOF
					)
				);
			}
		}
	}

	/**
	 * If both WPDb and WPLoader modules are in use, assert WPLoader's loadOnly is not false
	 *
	 * @param SuiteEvent $e
	 *
	 * @throws \Codeception\Exception\ModuleRequireException
	 */
	private function validateWPDbWithLoadOnly( SuiteEvent $e ) {
		$load_only = $this->getModule( 'WPLoader' )->_getConfig( 'loadOnly' );

		if ( $load_only === false ) {
			$e->getSuite()->markTestSuiteSkipped( 'WPDb is not compatible with WPLoader when "loadOnly" is set to false.' );
		}
	}

}