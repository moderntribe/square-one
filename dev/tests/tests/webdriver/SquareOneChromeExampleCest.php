<?php

use Page\Webdriver\Home_Page_Example;
use Page\Webdriver\Search_Page_Example;

class SquareOneChromeExampleCest extends Base_Webdriver_Cest {

	public function i_can_set_the_site_title_in_the_customizer( WebDriverTester $I ) {
		$site_title = __METHOD__ . rand( 0, 1000 );

		$I->makeScreenshot();
		$I->loginAsAdmin();
		$I->amOnPage( '/' );
		$I->click( '#wp-admin-bar-customize a' );
		$I->makeScreenshot();
		$I->click( '#accordion-section-title_tagline' );
		$I->waitForElementVisible( '#_customize-input-blogname', 3 );
		$I->fillField( '#_customize-input-blogname', $site_title );
		$I->click( '#save' );
		$I->waitForJqueryAjax();
		$I->makeScreenshot( __LINE__ . ' - After Saving' );
		$I->assertEquals( $site_title, $I->grabOptionFromDatabase( 'blogname' ) );
	}

	public function can_search_on_homepage( WebDriverTester $I, Home_Page_Example $home_page, Search_Page_Example $search_page ) {
		$search_query = 'FooBarBaz';
		$home_page->search( $search_query );
		$search_page->see_search_query( $search_query );
	}
}
