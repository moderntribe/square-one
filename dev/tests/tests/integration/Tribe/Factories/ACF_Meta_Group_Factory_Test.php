<?php

use Tribe\Tests\Test_Case;
use Tribe\Project\Service_Providers\Object_Meta_Provider;

/**
 * Class ACF_Meta_Group_Factory_Test
 *
 * Tests for the ACF_Meta_Group factory, given its complexity.
 */
class ACF_Meta_Group_Factory_Test extends Test_Case {

	public function setUp() {
		parent::setUp();

		$this->factory()->acf_field      = new \Tribe\Tests\Factories\ACF_Field;
		
		try {
			$this->factory()->acf_meta_group = new \Tribe\Tests\Factories\ACF_Meta_Group();
		} catch(Exception $e) {
			$this->markTestSkipped($e->getMessage());
		}
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * Helper function to return an instance of Object_Meta_Provider that
	 * does not register anything automatically.
	 *
	 * @return Object_Meta_Provider
	 */
	protected function get_provider() {
		return new class extends Object_Meta_Provider {
			public function register( \Pimple\Container $container ) {
				$this->repo( $container );
			}
		};
	}

	/** @test */
	public function should_register_group() {
		$group_before_insertion = acf_get_field_group( 'group_foo' );

		$this->factory()->acf_meta_group
			->with_name( 'foo' )
			->create();

		$group = acf_get_field_group( 'group_foo' );

		$this->assertFalse( $group_before_insertion );
		$this->assertIsArray( $group );
	}

	/** @test */
	public function should_register_group_with_fields() {
		$group_before_insertion = acf_get_field_group( 'group_foo' );

		$this->factory()->acf_meta_group
			->with_name( 'foo' )
			->with_fields(
				$this->factory()->acf_field->create(),
				$this->factory()->acf_field->create(),
				$this->factory()->acf_field->create()
			)
			->create();

		$group  = acf_get_field_group( 'group_foo' );
		$fields = acf_get_fields( $group );

		$this->assertFalse( $group_before_insertion );
		$this->assertIsArray( $group );
		$this->assertCount( 3, $fields );
	}

	/** @test */
	public function should_set_a_different_field_type() {
		$group_before_insertion = acf_get_field_group( 'group_foo' );

		// Create a random number of fields
		$textarea_field = $this->factory()->acf_field
			->with_name( 'foo_textarea' )
			->with_type( 'textarea' )
			->create();

		$this->factory()->acf_meta_group
			->with_name( 'foo' )
			->with_fields( $textarea_field )
			->create();

		$group  = acf_get_field_group( 'group_foo' );
		$fields = acf_get_fields( $group );

		$this->assertFalse( $group_before_insertion );
		$this->assertIsArray( $group );
		$this->assertCount( 1, $fields );
		$this->assertEquals( 'field_foo_textarea', $fields[0]['key'] );
		$this->assertEquals( 'textarea', $fields[0]['type'] );
	}

	/** @test */
	public function should_throw_if_no_name() {
		$this->expectException( Exception::class );

		$this->factory()->acf_meta_group->create();
	}

	/** @test */
	public function should_throw_if_invalid_field() {
		$this->expectException( TypeError::class );

		$this->factory()->acf_meta_group
			->with_name( 'foo' )
			->with_fields(
				'foo'
			)
			->create();
	}

	/** @test */
	public function should_delete_group_if_factory_instance_is_overriden() {
		$initial_state = $this->factory()->acf_meta_group->get_last_inserted_group();

		$instance = $this->factory()->acf_meta_group->with_name('foo')->create();
		$after_group_creation = $instance->get_last_inserted_group();

		$second_instance = $this->factory()->acf_meta_group = new \Tribe\Tests\Factories\ACF_Meta_Group();
		$after_new_instance = $second_instance->get_last_inserted_group();

		$this->factory()->acf_meta_group->with_name('foo')->create();
		$after_group_creation_in_new_instance = $second_instance->get_last_inserted_group();

		// Should be empty when we start
		$this->assertEmpty($initial_state);

		// Should have something when we declare something
		$this->assertNotEmpty($after_group_creation);

		// Should be empty when we create a new instance of the factory
		$this->assertEmpty($after_new_instance);

		// Should have something when we declare something again
		$this->assertNotEmpty($after_group_creation_in_new_instance);
	}

}