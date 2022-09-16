<?php

use Codeception\Util\HttpCode;

class SquareOneExampleCest {

	public function it_shows_the_postname_on_the_post_page( AcceptanceTester $I ) {
		$title = 'Hey there, this is a sample post.';

		$post_id = $I->havePostInDatabase( [
			'post_title' => $title,
		] );

		$I->seePostInDatabase( [
			'ID' => $post_id,
		] );

		$I->amOnPage( "/?p=$post_id" );
		$I->seeResponseCodeIs( HttpCode::OK );
		$I->see( $title );
	}

}
