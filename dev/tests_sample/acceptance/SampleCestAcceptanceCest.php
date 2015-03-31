<?php
use AcceptanceTester;

class SampleCestAcceptanceCest {
	public function _before( AcceptanceTester $I ) {
	}

	public function _after( AcceptanceTester $I ) {
	}

	/**
	 * @test
	 * it should allow accessing the admin area of the site
	 */
	public function it_should_allow_accessing_the_admin_area_of_the_site( AcceptanceTester $I ) {
		$I->wantTo( "make sure I'm in the admin area" );
		$I->loginAsAdmin();
		$I->seeElement( 'body.wp-admin' );
	}

	/**
	 * @test
	 * it should allow failing tests
	 */
	public function it_should_allow_failing_tests( AcceptanceTester $I ) {
		$I->wantTo( "see a non existing element" );
		$I->amOnPage( '/' );
		$I->seeElement( '#not-existing-element' );
	}
}