<?php

use Codeception\Util\HttpCode;

class SquareOneExampleCest extends BaseAcceptanceCest {

	const POST_TITLE = 'Hey there, this is a sample post.';

	public function it_shows_the_postname_on_the_homepage( AcceptanceTester $I ) {
		$post_id = $I->havePostInDatabase([
			'post_title' => self::POST_TITLE,
		]);

		$I->seePostInDatabase([
			'ID' => $post_id
		]);

		$I->amOnPage('/');
		$I->seeResponseCodeIs( HttpCode::OK );
		$I->see( self::POST_TITLE );
	}

	public function i_can_login_as_admin( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnAdminPage('/');
		$I->seeElement( '.dashicons-dashboard' );
	}

}
