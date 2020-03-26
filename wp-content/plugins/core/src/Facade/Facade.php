<?php

namespace Tribe\Project\Facade;

abstract class Facade {

	protected static $is_mocked = false;
	protected static $mock;

	/**
	 * Get the key used to access the concrete class within the Pimple container.
	 *
	 * @return string
	 */
	abstract public static function get_container_key_accessor();

	/**
	 * Get the base Namespace for the concrete class. Can be overwritten in the child class to be a hardcoded classname
	 * to avoid requiring the tribe_project() method when testing.
	 *
	 * @return string
	 */
	protected static function get_base_namespace() {
		return get_class( self::resolve_base_class() );
	}

	/**
	 * Get the base class instance from the container.
	 *
	 * @return mixed
	 */
	protected static function resolve_base_class() {
		return tribe_project()->container()->get( static::get_container_key_accessor() );
	}

	/**
	 * When called statically, return the mock object (if available); otherwise return the resolved base class from the
	 * container.
	 *
	 * @param $function
	 * @param $args
	 *
	 * @return mixed
	 */
	public static function __callStatic( $function, $args ) {
		if ( static::$is_mocked ) {
			return call_user_func_array( [ static::$mock, $function ], $args );
		}
		return call_user_func_array( [ self::resolve_base_class(), $function ], $args );
	}

	/**
	 * @see \Codeception\Stub::make()
	 *
	 * @param array ...$args
	 *
	 * @throws \Exception
	 */
	public static function make( ...$args ) {
		if ( ! self::can_mock() ) {
			static::$is_mocked = false;
			return;
		}

		static::$mock      = \Codeception\Stub::make( static::get_base_namespace(), ...$args );
		static::$is_mocked = true;
	}

	/**
	 * @see \Codeception\Stub::makeEmpty()
	 *
	 * @param array ...$args
	 *
	 * @throws \Exception
	 */
	public static function makeEmpty( ...$args ) {
		if ( ! self::can_mock() ) {
			static::$is_mocked = false;
			return;
		}

		static::$mock      = \Codeception\Stub::makeEmpty( static::get_base_namespace(), ...$args );
		static::$is_mocked = true;
	}

	/**
	 * @see \Codeception\Stub::makeEmptyExcept()
	 *
	 * @param array ...$args
	 *
	 * @throws \Exception
	 */
	public static function makeEmptyExcept( ...$args ) {
		if ( ! self::can_mock() ) {
			static::$is_mocked = false;
			return;
		}

		static::$mock      = \Codeception\Stub::makeEmptyExcept( static::get_base_namespace(), ...$args );
		static::$is_mocked = true;
	}

	/**
	 * @see \Codeception\Stub::construct()
	 *
	 * @param array ...$args
	 *
	 * @throws \Exception
	 */
	public static function construct( ...$args ) {
		if ( ! self::can_mock() ) {
			static::$is_mocked = false;
			return;
		}

		static::$mock      = \Codeception\Stub::construct( static::get_base_namespace(), ...$args );
		static::$is_mocked = true;
	}

	/**
	 * @see \Codeception\Stub::constructEmpty()
	 *
	 * @param array ...$args
	 *
	 * @throws \Exception
	 */
	public static function constructEmpty( ...$args ) {
		if ( ! self::can_mock() ) {
			static::$is_mocked = false;
			return;
		}

		static::$mock      = \Codeception\Stub::constructEmpty( static::get_base_namespace(), ...$args );
		static::$is_mocked = true;
	}

	/**
	 * @see \Codeception\Stub::constructEmptyExcept()
	 *
	 * @param array ...$args
	 *
	 * @throws \Exception
	 */
	public static function constructEmptyExcept( ...$args ) {
		if ( ! self::can_mock() ) {
			static::$is_mocked = false;
			return;
		}

		static::$mock      = \Codeception\Stub::constructEmptyExcept( static::get_base_namespace(), ...$args );
		static::$is_mocked = true;
	}

	/**
	 * Destroys the existing mock in order to restore base class functionality.
	 */
	public static function destroy_mock() {
		static::$mock      = null;
		static::$is_mocked = false;
	}

	/**
	 * Whether the class can be mocked using Codeception\Stub.
	 *
	 * @return bool
	 */
	protected static function can_mock() {
		return class_exists( '\Codeception\Stub' );
	}
}
