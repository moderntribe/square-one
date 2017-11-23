<?php

namespace Tribe\Project\Permissions\Roles;

use Tribe\Project\Permissions\Taxonomies\Section\Section;
use Tribe\Project\Post_Types\Page\Page;

class Section_EditorTest extends \Codeception\TestCase\WPTestCase {

	public function test_can_edit_pages() {
		/** @var \WP_User $user */
		$user = $this->factory()->user->create_and_get();
		$term = $this->factory()->term->create_and_get( [ 'taxonomy' => Section::NAME ] );

		$section = Section::factory( $term->term_id );
		$section->set_role( $user, Section_Editor::NAME );

		$post = $this->factory()->post->create_and_get( [
			'post_type'   => Page::NAME,
			'post_status' => 'publish',
		] );

		$this->assertFalse( $user->has_cap( 'edit_post', $post->ID ) );

		wp_set_object_terms( $post->ID, [ $term->term_id ], Section::NAME );

		$this->assertTrue( $user->has_cap( 'edit_post', $post->ID ) );
		$this->assertTrue( $user->has_cap( 'publish_post', $post->ID ) );
		$this->assertTrue( $user->has_cap( 'delete_post', $post->ID ) );
	}

}