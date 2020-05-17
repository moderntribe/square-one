<?php

namespace Page\Webdriver;

/**
 * Class Home_Page_Example
 *
 * This is an example of a Page Object.
 *
 * A Page Object in codeception is a class whose purpose is to abstract certain
 * behavior on a given page. The idea is that you code your webdiver/acceptance
 * tests using them, instead of relying on hardcoded identifiers such as
 * ".someclass .somchild" on all the tests. When you delegate that knowledge
 * to a Page Object class, if the HTML output change you just have to change
 * the corresponding Page Object class for all your tests to keep on shining!
 *
 * @package Page\Webdriver
 */
class Home_Page_Example {
	public static $URL = '/';

	/**
	 * @var \WebDriverTester;
	 */
	protected $webDriverTester;

	public function __construct( \WebDriverTester $I ) {
		$this->webDriverTester = $I;
	}

	public function search( string $search_query ) {
		$this->webDriverTester->fillField( '.c-search #s', $search_query );

		// Seems clicking the buttom isn't triggering the search, but it should.
		// $this->webDriverTester->click( '.c-search button' );

		/*
		 * So, let's hack our way in by submitting the form, this is not ideal because it's not
		 * how a normal user would use the site, so we'd want to fix the button and delete these notes.
		 */
		$this->webDriverTester->submitForm( 'form.c-search', [
			's' => $search_query,
		] );
	}

}
