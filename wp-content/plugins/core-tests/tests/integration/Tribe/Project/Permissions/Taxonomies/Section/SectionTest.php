<?php
namespace Tribe\Project\Permissions\Taxonomies\Section;

class SectionTest extends \Codeception\TestCase\WPTestCase {
	public function test_no_role_to_get() {
		$user = $this->factory()->user->create_and_get();
		$term = $this->factory()->term->create_and_get([ 'taxonomy' => Section::NAME ] );

		$section = Section::factory( $term->term_id );

		$this->assertEmpty( $section->get_role( $user ) );
	}

	public function test_set_role() {
		$user = $this->factory()->user->create_and_get();
		$term = $this->factory()->term->create_and_get([ 'taxonomy' => Section::NAME ] );

		$section = Section::factory( $term->term_id );
		$section->set_role( $user, 'test_role' );

		$this->assertEquals( 'test_role', $section->get_role( $user ) );
	}

	public function test_update_role() {
		$user = $this->factory()->user->create_and_get();
		$term = $this->factory()->term->create_and_get([ 'taxonomy' => Section::NAME ] );

		$section = Section::factory( $term->term_id );
		$section->set_role( $user, 'first_role' );
		$section->set_role( $user, 'second_role' );

		$this->assertEquals( 'second_role', $section->get_role( $user ) );
	}

	public function test_multiple_sections() {
		$user = $this->factory()->user->create_and_get();
		$first_term = $this->factory()->term->create_and_get([ 'taxonomy' => Section::NAME ] );
		$second_term = $this->factory()->term->create_and_get([ 'taxonomy' => Section::NAME ] );

		$first_section = Section::factory( $first_term->term_id );
		$first_section->set_role( $user, 'first_role' );

		$second_section = Section::factory( $second_term->term_id );
		$second_section->set_role( $user, 'second_role' );

		$this->assertEquals( 'first_role', $first_section->get_role( $user ) );
		$this->assertEquals( 'second_role', $second_section->get_role( $user ) );
	}

	public function test_get_users() {
		$group_a_user_ids = $this->factory()->user->create_many( 3 );
		$group_b_user_ids = $this->factory()->user->create_many( 3 );

		$group_a_term = $this->factory()->term->create_and_get([ 'taxonomy' => Section::NAME ] );
		$group_b_term = $this->factory()->term->create_and_get([ 'taxonomy' => Section::NAME ] );

		$section_a = Section::factory( $group_a_term->term_id );
		$section_b = Section::factory( $group_b_term->term_id );

		foreach ( $group_a_user_ids as $user_id ) {
			$user = new \WP_User( $user_id );
			$section_a->set_role( $user, 'role_a' );
		}
		foreach ( $group_b_user_ids as $user_id ) {
			$user = new \WP_User( $user_id );
			$section_b->set_role( $user, 'role_b' );
		}

		// each section should have the users from the given group
		$this->assertEqualSets( $group_a_user_ids, $section_a->get_users( 'role_a' ) );
		$this->assertEqualSets( $group_b_user_ids, $section_b->get_users( 'role_b' ) );

		// each section should not have users from the other group
		$this->assertEmpty( $section_a->get_users( 'role_b' ) );
		$this->assertEmpty( $section_b->get_users( 'role_a' ) );

		foreach ( $group_b_user_ids as $user_id ) {
			$user = new \WP_User( $user_id );
			$section_a->set_role( $user, 'role_b' );
		}

		// adding users with a different role should still keep them separate, even in the same section
		$this->assertEqualSets( $group_a_user_ids, $section_a->get_users( 'role_a' ) );
		$this->assertEqualSets( $group_b_user_ids, $section_a->get_users( 'role_b' ) );
	}

}