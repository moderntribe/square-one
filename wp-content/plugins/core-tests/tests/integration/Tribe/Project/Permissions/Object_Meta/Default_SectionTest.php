<?php

namespace Tribe\Project\Permissions\Object_Meta;

use Tribe\Project\Permissions\Taxonomies\Section\Section;

class Default_SectionTest extends \Codeception\TestCase\WPTestCase {

	public function test_default_term() {
		$automatic_term = get_term_by( 'slug', Section::DEFAULT, Section::NAME );

		$term_id = get_option( Default_Section::TERM_ID );
		$this->assertEquals( $automatic_term->term_id, $term_id );
	}

	public function test_no_term_available() {
		$terms = get_terms( [
			'taxonomy'   => Section::NAME,
			'hide_empty' => false,
		] );
		array_walk( $terms, function ( $term ) {
			wp_delete_term( $term->term_id, $term->taxonomy );
		} );

		$term_id = get_option( Default_Section::TERM_ID );
		$this->assertEquals( 0, $term_id );
	}

	public function test_alternative_term() {
		$terms = get_terms( [
			'taxonomy'   => Section::NAME,
			'hide_empty' => false,
		] );
		array_walk( $terms, function ( $term ) {
			wp_delete_term( $term->term_id, $term->taxonomy );
		} );
		$new_term_id = $this->factory()->term->create( [
			'taxonomy' => Section::NAME,
		] );

		$term_id = get_option( Default_Section::TERM_ID );
		$this->assertEquals( $new_term_id, $term_id );
	}

	public function test_removed_term() {
		$automatic_term = get_term_by( 'slug', Section::DEFAULT, Section::NAME );
		$new_term_id    = $this->factory()->term->create( [
			'taxonomy' => Section::NAME,
		] );
		add_option( Default_Section::TERM_ID, $automatic_term->term_id );

		$term_id = get_option( Default_Section::TERM_ID );
		$this->assertEquals( $automatic_term->term_id, $term_id );

		wp_delete_term( $term_id, Section::NAME );

		$term_id = get_option( Default_Section::TERM_ID );
		$this->assertEquals( $new_term_id, $term_id );

	}

}