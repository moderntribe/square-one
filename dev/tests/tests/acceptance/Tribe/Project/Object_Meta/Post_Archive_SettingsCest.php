<?php

namespace Tribe\Project\Theme;

use AcceptanceTester;

class Post_Archive_SettingsCest {
	public function _before( AcceptanceTester $I ) {
	}

	public function post_settings_should_have_settings( AcceptanceTester $I ) {
		$I->loginAsAdmin();
		$I->amOnAdminPage('edit.php?page=edit-php-settings');
		$I->seeElement( '#acf-group_post_archive_settings' );
		$I->seeElement( '#acf-group_post_archive_featured_settings' );
	}
}
