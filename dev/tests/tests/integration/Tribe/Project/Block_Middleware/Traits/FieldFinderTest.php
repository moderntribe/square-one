<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware\Traits;

use Tribe\Libs\ACF\Field_Group;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\ACF\Repeater;
use Tribe\Tests\Fixtures\FieldFinder;
use Tribe\Tests\Test_Case;

final class FieldFinderTest extends Test_Case {

	public function test_it_finds_top_level_field(): void {
		$repeater = new Repeater( 'test_repeater' );

		$finder = new FieldFinder( [
			$repeater,
		] );

		$this->assertSame( $repeater, $finder->find( 'field_test_repeater' ) );
	}

	public function test_it_finds_fields_multiple_levels_deep(): void {
		$group               = new Field_Group('test_group' );
		$section             = new Field_Section( 's-test-section', 'Test Section', 'accordion' );
		$parent_repeater     = new Repeater( 'parent_repeater' );
		$child_repeater      = new Repeater( 'child_repeater' );
		$grandchild_repeater = new Repeater( 'grandchild_repeater' );

		$child_repeater->add_field( $grandchild_repeater );
		$parent_repeater->add_field( $child_repeater );
		$section->add_field( $parent_repeater );

		$finder = new FieldFinder( [
			$group,
			$section,
		] );

		$this->assertSame( $section, $finder->find( 'section__s-test-section' ) );
		$this->assertSame( $parent_repeater, $finder->find( 'field_parent_repeater' ) );
		$this->assertSame( $child_repeater, $finder->find( 'field_child_repeater' ) );
		$this->assertSame( $grandchild_repeater, $finder->find( 'field_grandchild_repeater' ) );
		$this->assertSame( $group, $finder->find( 'field_test_group' ) );
		$this->assertNull( $finder->find( 'unknown_field_key' ) );
	}

	public function test_it_finds_top_level_fields_in_multi_level_deep_fields(): void {
		$group               = new Field_Group( 'test_group' );
		$section             = new Field_Section( 's-test-section', 'Test Section', 'accordion' );
		$section_to_find     = new Field_Section( 's-section-to-find', 'Find me', 'accordion' );
		$parent_repeater     = new Repeater( 'parent_repeater' );
		$child_repeater      = new Repeater( 'child_repeater' );
		$grandchild_repeater = new Repeater( 'grandchild_repeater' );

		$child_repeater->add_field( $grandchild_repeater );
		$parent_repeater->add_field( $child_repeater );
		$section->add_field( $parent_repeater );

		$finder = new FieldFinder( [
			$group,
			$section,
			$section_to_find,
		] );

		$this->assertSame( $section_to_find, $finder->find( 'section__s-section-to-find' ) );
	}

}
