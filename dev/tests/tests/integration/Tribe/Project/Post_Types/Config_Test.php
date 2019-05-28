<?php

use Tribe\Tests\Test_Case;
use Tribe\Tests\Post_Types\Test_CPT\Test_CPT;
use Tribe\Tests\Post_Types\Test_CPT\Config;

class Config_Test extends Test_Case {
	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		unregister_post_type( Test_CPT::NAME );
	}

	protected function make_instance() {
		return new Config( Test_CPT::NAME );
	}

	/** @test */
	public function should_be_instantiable() {
		$this->assertInstanceOf( Config::class, $this->make_instance() );
	}

	/** @test */
	public function should_register_the_post_type() {
		$exists_before = post_type_exists( Test_CPT::NAME );

		$config = $this->make_instance();
		$config->register();

		$exists_after = post_type_exists( Test_CPT::NAME );

		$this->assertFalse( $exists_before );
		$this->assertTrue( $exists_after );
	}

	/** @test */
	public function should_register_the_post_type_with_custom_args() {
		$args = [
			'hierarchical'    => false,
			'map_meta_cap'    => true,
			'capability_type' => 'post',
			'menu_icon'       => 'some-icon',
		];
		add_filter( 'tribe_test_cpt_args', function () use ( $args ) {
			return $args;
		} );

		$config = $this->make_instance();
		$config->register();

		$object = get_post_type_object( Test_CPT::NAME );

		$this->assertInstanceOf( \WP_Post_Type::class, $object );
		foreach ( $args as $key => $value ) {
			$this->assertEquals( $value, $object->$key );
		}
	}

	/** @test */
	public function should_register_the_post_type_with_custom_labels() {
		$labels = [
			'singular' => 'Test CPT Singular',
			'plural'   => 'Test CPT Plural',
			'slug'     => 'Test CPT Slug',
		];
		add_filter( 'tribe_test_cpt_labels', function () use ( $labels ) {
			return $labels;
		} );

		$config = $this->make_instance();
		$config->register();

		$object = get_post_type_object( Test_CPT::NAME );

		$this->assertInstanceOf( \WP_Post_Type::class, $object );
		foreach ( $labels as $key => $value ) {
			$this->assertEquals( $value, $object->labels->$key );
		}
	}
}