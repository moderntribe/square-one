<?php


namespace JSONLD;


class Organization_Data_Builder extends Data_Builder {

	// todo: this will move most likely to wherever the settings page is defined in this plugin
	// since we dont want this feature enabled on this site i am happy with false for now

	const SEARCHACTION_ENABLED_OPTION_KEY = 'tribe_jsonld_search_action_enabled';

	protected function build_data() {

		$search_action_enabled = ( $this->get_option( Settings_Page::ENABLE_SEARCH_ACTION ) === 'yes' );

		//todo: lets move everything we can to settings in the admin. name, copy holder and the social profiles too

		$data = [
			'@context'        => 'https://schema.org',
			'@type'           => 'Organization',
			'name'            => $this->get_option( Settings_Page::ORG_NAME ),
			'logo'            => $this->get_organization_logo(),
			'url'             => home_url(),
			'sameAs' => [
				'https://plus.google.com/u/0/115379189893046057301',
				'https://www.linkedin.com/company/modern-tribe-inc-',
				'https://www.facebook.com/ModernTribeInc/',
			]

		];

		// Add the site search action data
		if ( is_front_page() && $search_action_enabled ) {
			$data['potentialAction'] = [
				'@type'         => 'SearchAction',
                'target'        => trailingslashit( home_url() ) . '?s={search_term_string}',
                'query-input'   => 'required name=search_term_string',
			];
		}

		return $data;
	}

	/**
	 * TODO Later: Corporate Contacts?
	 * https://developers.google.com/structured-data/customize/contact-points
	 */

}