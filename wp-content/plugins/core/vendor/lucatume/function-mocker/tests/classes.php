<?php
	namespace tad\FunctionMocker\Tests;

	class AClass {

		public static function staticMethod() {
			return false;
		}

		public function instanceMethod() {
			return 112;
		}
	}


	class SomeClass {

		public static function staticMethod( $a = null, $b = null ) {
			return "foo baz";
		}

		public function instanceMethod() {
			return 'some value';
		}
	}


	class SomeClassExtension extends SomeClass {

		public function instanceMethod() {
			return 'foo';
		}
	}


	class AnotherClass {

		public function someMethod() {
		}
	}


	function someFunction( $value1 = 0, $value2 = 0 ) {
		return 'foo';
	}

	function anotherFunction() {

	}
