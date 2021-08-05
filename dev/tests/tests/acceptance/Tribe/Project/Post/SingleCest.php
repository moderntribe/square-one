<?php

namespace Tribe\Project\Post;

use AcceptanceTester;
use DateTime;
use DateTimeZone;

class SingleCest {

	public function _before( AcceptanceTester $I ) {
	}

	public function post_should_display_date_and_author( AcceptanceTester $I ) {
		$post_id = $I->havePostInDatabase( [
			'post_status' => 'publish',
		] );

		$posts_table = $I->grabPostsTableName();
		$post_date   = $I->grabFromDatabase( $posts_table, 'post_date', [
			'ID' => $post_id,
		] );

		$options_table = $I->grabPrefixedTableNameFor( 'options' );
		$date_format   = $I->grabFromDatabase( $options_table, 'option_value', [
			'option_name' => 'date_format',
		] );

		$date = new DateTime( $post_date, new DateTimeZone( 'UTC' ) );

		$I->amOnPage( "/?p=$post_id" );
		$I->see( 'by admin', '.item-single__meta-author' );
		$I->see( $date->format( $date_format ), 'time' );
		$I->seeInSource( sprintf( '<time datetime="%s">', $date->format( 'c' ) ) );
	}

}
