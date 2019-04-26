<?php

namespace Tribe\Tests\Service_Providers\Post_Types;

use Pimple\Container;
use Prophecy\Argument;
use Tribe\Tests\Post_Types\Test_CPT\Test_CPT;
use Tribe\Tests\SquareOneTestCase;

class Test_CPT_Service_Provider_Test extends SquareOneTestCase {

	protected $original_providers;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		unregister_post_type( Test_CPT::NAME );
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/** @test */
	public function should_call_register() {
		$mock                         = $this->prophesize( Test_CPT_Service_Provider::class );
		$to_replace_service_providers = function () use ( $mock ) {
			return [ 'post_types.test_cpt' => $mock->reveal() ];
		};
		add_filter( 'tribe/project/providers', $to_replace_service_providers );

		tribe_project()->init();

		$mock->register( Argument::type( Container::class ) )->shouldHaveBeenCalledOnce();
	}

	/** @test */
	public function should_register_the_post_type() {
		$container        = tribe_project()->container();
		$service_provider = new Test_CPT_Service_Provider();
		$service_provider->register( $container );
		$container['post_type.test_cpt.config']->register();

		$exists = post_type_exists( Test_CPT::NAME );

		$this->assertTrue( $exists );
	}

	/** @test */
	public function should_register_the_post_type_with_custom_args() {
		$container        = tribe_project()->container();
		$service_provider = new Test_CPT_Service_Provider();
		$args             = [
			'hierarchical'    => false,
			'map_meta_cap'    => true,
			'capability_type' => 'post',
			'menu_icon'       => 'some-icon',
		];
		add_filter( 'tribe_test_cpt_args', function () use ( $args ) {
			return $args;
		} );

		$exists_before = post_type_exists( Test_CPT::NAME );
		$service_provider->register( $container );
		$container['post_type.test_cpt.config']->register();
		$object = get_post_type_object( Test_CPT::NAME );

		$this->assertFalse( $exists_before );
		$this->assertInstanceOf( \WP_Post_Type::class, $object );
		foreach ( $args as $key => $value ) {
			$this->assertEquals( $value, $object->$key );
		}
	}

	/** @test */
	public function should_register_the_post_type_with_custom_labels() {
		$container        = tribe_project()->container();
		$service_provider = new Test_CPT_Service_Provider();
		$labels           = [
			'singular' => 'Test CPT Singular',
			'plural'   => 'Test CPT Plural',
			'slug'     => 'Test CPT Slug',
		];
		add_filter( 'tribe_test_cpt_labels', function () use ( $labels ) {
			return $labels;
		} );

		$exists_before = post_type_exists( Test_CPT::NAME );
		$service_provider->register( $container );
		$container['post_type.test_cpt.config']->register();
		$object = get_post_type_object( Test_CPT::NAME );

		$this->assertFalse( $exists_before );
		$this->assertInstanceOf( \WP_Post_Type::class, $object );
		foreach ( $labels as $key => $value ) {
			$this->assertEquals( $value, $object->labels->$key );
		}
	}
}