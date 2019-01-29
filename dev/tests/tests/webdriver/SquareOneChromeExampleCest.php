<?php 

class SquareOneChromeExampleCest {

	const SITE_TITLE        = 'This is Sparta';
	const FIELD_SITE_TITLE  = '#_customize-input-blogname';

	public function _before( AcceptanceTester $I ) {
	}

	public function i_can_set_the_site_title_in_the_customizer( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnPage('/');
		$I->click( '#wp-admin-bar-customize a' );
		$I->click( '#accordion-section-title_tagline' );
		$I->fillField(self::FIELD_SITE_TITLE, self::SITE_TITLE );
		$I->click( '#save' );
		$I->wait( 3 );
		$I->assertEquals( self::SITE_TITLE, $I->grabOptionFromDatabase('blogname') );
	}
}
