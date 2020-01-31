<?php 

class SquareOneChromeExampleCest extends Base_Webdriver_Cest {

	public function i_can_set_the_site_title_in_the_customizer( WebDriverTester $I ) {
		$site_title = wp_generate_password();

		$I->makeScreenshot( __LINE__ . ' - Start' );
		$I->loginAsAdmin();
		$I->amOnPage( '/' );
		$I->click( '#wp-admin-bar-customize a' );
		$I->makeScreenshot( __LINE__ . ' - On Customizer' );
		$I->click( '#accordion-section-title_tagline' );
		$I->waitForElementVisible( '#_customize-input-blogname', 3 );
		$I->fillField( '#_customize-input-blogname', $site_title );
		$I->click( '#save' );
		$I->waitForJqueryAjax();
		$I->makeScreenshot( __LINE__ . ' - After Saving' );
		$I->assertEquals( $site_title, $I->grabOptionFromDatabase( 'blogname' ) );
	}
}
