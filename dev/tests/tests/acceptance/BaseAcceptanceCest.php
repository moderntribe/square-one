<?php
/**
 * Class BaseAcceptanceCest
 *
 * All Acceptance tests should extend this class in order for the proper header to be passed to Square One so the
 * tests-config.php file gets loaded dynamically.
 *
 */

class BaseAcceptanceCest
{
	const HTTP_HEADER = 'X-TRIBE-TESTING';

	public function _before( AcceptanceTester $I ) {
		$I->haveHttpHeader( self::HTTP_HEADER, 'Tribe' );
	}
}
