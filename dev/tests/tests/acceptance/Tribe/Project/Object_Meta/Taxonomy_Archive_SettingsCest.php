<?php

namespace Tribe\Project\Theme;

use AcceptanceTester;

class Taxonomy_Archive_SettingsCest {
	public function _before( AcceptanceTester $I ) {
	}

	public function category_should_have_hero_image_field( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnAdminPage('/term.php?taxonomy=category&tag_ID=1&post_type=post');
		$I->seeElement( '.acf-field-taxonomy-archive-settings-hero-image' );
	}
}
