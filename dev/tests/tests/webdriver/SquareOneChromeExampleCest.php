<?php

class SquareOneChromeExampleCest extends Base_Webdriver_Cest {

	public function i_can_set_the_site_title_in_the_customizer( WebDriverTester $I ) {
		$site_title = 'This is a test';

		$I->makeScreenshot();
		$I->loginAsAdmin();
		$I->amOnPage( '/' );
		$I->click( '#wp-admin-bar-customize a' );
		$I->makeScreenshot();
		$I->click( '#accordion-section-title_tagline' );
		$I->waitForElementVisible( '#_customize-input-blogname', 20 );
		$I->fillField( '#_customize-input-blogname', $site_title );
		$I->click( '#save' );
		$I->waitForJqueryAjax( 20 );
		$I->makeScreenshot( __LINE__ . ' - After Saving' );
		$I->assertEquals( $site_title, $I->grabOptionFromDatabase( 'blogname' ) );
	}

	public function i_can_view_the_search_results_page_with_no_results( WebDriverTester $I ) {
		$I->amOnPage( '/search/missing%20term' );
		$I->seeElement( '.c-search--full' );
		$I->seeInField( '.c-search__input', 'missing term' );
		$I->seeInSource( 'returned 0 results' );
	}
}
