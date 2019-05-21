<?php

namespace Tribe\Project\Service_Providers;

use Tribe\Tests\SquareOneTestCase;
use Tribe\Libs\Object_Meta\Meta_Group;

/**
 * Class Object_Meta_Provider_Test
 *
 * This class holds tests for the Object_Meta_Provider itself, such as the declaration of its constants and $keys array.
 *
 * Generic tests for ACF_Meta_Group classes are run in a separate test class.
 * @see     \ACF_Meta_Groups_In_Provider_Test
 *
 * @package Tribe\Project\Service_Providers
 */
class Object_Meta_Provider_Test extends SquareOneTestCase {
	/** @test */
	public function should_be_instantiable() {
		$this->assertInstanceOf( Object_Meta_Provider::class, $this->make_instance() );
	}

	protected function make_instance() {
		return new Object_Meta_Provider();
	}

	/**
	 * Returns an array of instances of Meta_Group registered by the Provider
	 */
	protected function get_meta_groups() {
		$meta_objects = [];
		$om_provider  = $this->make_instance();
		$keys         = $om_provider->get_keys();

		foreach ( $keys as $key ) {
			$meta_objects[] = tribe_project()->container()[ $key ];
		}

		return $meta_objects;
	}

	/** @test */
	public function keys_should_be_an_instance_of_meta_group() {
		$container   = tribe_project()->container();
		$om_provider = $this->make_instance();
		$keys        = $om_provider->get_keys();

		foreach ( $keys as $key ) {
			$this->assertInstanceOf( Meta_Group::class, $container[ $key ] );
		}
	}

	/** @test */
	public function constants_that_extend_from_meta_group_should_be_declared_in_keys_array() {
		$container   = tribe_project()->container();
		$om_provider = $this->make_instance();
		$keys        = $om_provider->get_keys();

		$class_reflex = new \ReflectionClass( $om_provider );
		foreach ( $class_reflex->getConstants() as $class_constant ) {
			if ( $container->offsetExists( $class_constant ) ) {
				$constant_under_test = $container[ $class_constant ];
				if ( $constant_under_test instanceof Meta_Group ) {
					$this->assertTrue( in_array( $class_constant, $keys ), sprintf(
						"Constant %s extends from Meta_Group, thus must be declared in Object_Meta_Provider::\$keys array.",
						$class_constant
					) );
				}
			}
		}
	}

	/** @test */
	public function keys_should_be_registered_in_the_container() {
		$container   = tribe_project()->container();
		$om_provider = $this->make_instance();
		$keys        = $om_provider->get_keys();

		foreach ( $keys as $key ) {
			$this->assertTrue( $container->offsetExists( $key ) );
		}
	}
}