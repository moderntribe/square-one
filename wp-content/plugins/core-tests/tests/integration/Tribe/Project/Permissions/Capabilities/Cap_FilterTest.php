<?php

namespace Tribe\Project\Permissions\Taxonomies\Section;

class Cap_FilterTest extends \Codeception\TestCase\WPTestCase {
	public function test_administrator() {
		/** @var \WP_User $user */
		$user = $this->factory()->user->create_and_get();
		$user->set_role( 'administrator' );

		$taxonomy = get_taxonomy( Section::NAME );
		$this->assertTrue( $user->has_cap( $taxonomy->cap->manage_terms ) );
		$this->assertTrue( $user->has_cap( $taxonomy->cap->edit_terms ) );
		$this->assertTrue( $user->has_cap( $taxonomy->cap->delete_terms ) );
		$this->assertTrue( $user->has_cap( $taxonomy->cap->assign_terms ) );
	}

	public function test_subscriber() {
		/** @var \WP_User $user */
		$user = $this->factory()->user->create_and_get();

		$taxonomy = get_taxonomy( Section::NAME );
		$this->assertFalse( $user->has_cap( $taxonomy->cap->manage_terms ) );
		$this->assertFalse( $user->has_cap( $taxonomy->cap->edit_terms ) );
		$this->assertFalse( $user->has_cap( $taxonomy->cap->delete_terms ) );
		$this->assertFalse( $user->has_cap( $taxonomy->cap->assign_terms ) );
	}

}