<?php

namespace Tribe\Project\Theme;

use AcceptanceTester;

class Post_Archive_SettingsCest {
	public function _before( AcceptanceTester $I ) {
	}

	public function general_settings_should_have_archive_fields( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnAdminPage('options-general.php?page=options-general-php-general-settings');
		$I->seeElement( '#acf-group_post_archive_settings' );
	}
}
