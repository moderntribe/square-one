<?php

namespace Page\Webdriver;

/**
 * Class Search_Page_Example
 *
 * See Home_Page_Example constructor notes for details.
 *
 * @see \Page\Webdriver\Home_Page_Example
 *
 * @package Page\Webdriver
 */
class Search_Page_Example {
	/**
	 * @var \WebDriverTester;
	 */
	protected $webDriverTester;

	public function __construct( \WebDriverTester $I ) {
		$this->webDriverTester = $I;
	}

	public function see_search_query( string $search_query ) {
		/*
		 * Ideally, we would extract this from the HTML output instead of the URL,
		 * but the HTML output does not provide us that information on a default S1.
		 */
		$this->webDriverTester->seeInCurrentUrl( "?s=" . urlencode( $search_query ) );
	}

}
