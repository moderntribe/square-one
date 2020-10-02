<?php

namespace Tribe\Project\Theme;

use AcceptanceTester;

class Body_ClassesCest {
	public function _before( AcceptanceTester $I ) {
	}

	public function body_class_should_have_post_slug( AcceptanceTester $I ) {
		$post_id = $I->havePostInDatabase( [
			'post_title' => 'first post',
			'post_name'  => 'first-post',
		] );

		$page_id = $I->havePostInDatabase( [
			'post_title' => 'first page',
			'post_type'  => 'page',
			'post_name'  => 'first-page',
		] );

		$I->amOnPage( "/?p=$post_id" );
		$I->seeElement( 'body.post-first-post' );

		$I->amOnPage( "/?page_id=$page_id" );
		$I->seeElement( 'body.page-first-page' );
	}
}
