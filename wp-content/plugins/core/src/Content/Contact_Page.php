<?php


namespace Tribe\Project\Content;

/**
 * Class Contact_Page
 *
 * This is an EXAMPLE of how to create a page that
 * will be automatically created.
 */
class Contact_Page extends Required_Page {
	const NAME = 'contact_page';

	protected function get_title() {
		return _x( 'Contact Us', 'contact page title', 'tribe' );
	}

	protected function get_slug() {
		return _x( 'contact', 'contact page slug', 'tribe' );
	}

}
