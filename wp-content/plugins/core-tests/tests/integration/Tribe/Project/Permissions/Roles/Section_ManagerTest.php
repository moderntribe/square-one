<?php

namespace Tribe\Project\Permissions\Roles;

use Tribe\Project\Permissions\Roles\Section_Editor;
use Tribe\Project\Permissions\Roles\Section_Manager;
use Tribe\Project\Permissions\Taxonomies\Section\Section;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Post\Post;

class Section_ManagerTest extends \Codeception\TestCase\WPTestCase {


	public function test_can_access_section_admin() {
		/** @var \WP_User $user */
		$user     = $this->factory()->user->create_and_get();
		$term     = $this->factory()->term->create_and_get( [ 'taxonomy' => Section::NAME ] );
		$taxonomy = get_taxonomy( Section::NAME );

		$section = Section::factory( $term->term_id );
		$section->set_role( $user, Section_Manager::NAME );

		$this->assertTrue( $user->has_cap( $taxonomy->cap->manage_terms ) );
	}

	public function test_can_edit_own_section() {
		/** @var \WP_User $user */
		$user = $this->factory()->user->create_and_get();
		$term = $this->factory()->term->create_and_get( [ 'taxonomy' => Section::NAME ] );

		$section = Section::factory( $term->term_id );
		$section->set_role( $user, Section_Manager::NAME );

		$this->assertTrue( $user->has_cap( 'edit_term', $term->term_id ) );
	}

	public function test_cannot_edit_other_section() {
		/** @var \WP_User $user */
		$user        = $this->factory()->user->create_and_get();
		$term        = $this->factory()->term->create_and_get( [ 'taxonomy' => Section::NAME ] );
		$anotherterm = $this->factory()->term->create_and_get( [ 'taxonomy' => Section::NAME ] );

		$section = Section::factory( $term->term_id );
		$section->set_role( $user, Section_Manager::NAME );

		$this->assertFalse( $user->has_cap( 'edit_term', $anotherterm->term_id ) );
	}

	public function test_can_edit_posts() {
		/** @var \WP_User $user */
		$user = $this->factory()->user->create_and_get();
		$term = $this->factory()->term->create_and_get( [ 'taxonomy' => Section::NAME ] );

		$section = Section::factory( $term->term_id );
		$section->set_role( $user, Section_Manager::NAME );

		$post = $this->factory()->post->create_and_get( [
			'post_type'   => Post::NAME,
			'post_status' => 'publish',
		] );

		$this->assertFalse( $user->has_cap( 'edit_post', $post->ID ) );

		wp_set_object_terms( $post->ID, [ $term->term_id ], Section::NAME );

		$this->assertTrue( $user->has_cap( 'edit_post', $post->ID ) );
		$this->assertTrue( $user->has_cap( 'publish_post', $post->ID ) );
		$this->assertTrue( $user->has_cap( 'delete_post', $post->ID ) );
	}
}