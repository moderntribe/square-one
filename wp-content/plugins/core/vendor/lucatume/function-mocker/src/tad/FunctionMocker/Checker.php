<?php

	namespace tad\FunctionMocker;

	class Checker {

		protected static $systemFunctions;
		protected        $functionName;
		protected        $isEvalCreated;

		public static function fromName( $functionName ) {
			if ( ! self::$systemFunctions ) {
				self::$systemFunctions = get_defined_functions()['internal'];
			}
			$condition = ! in_array( $functionName, self::$systemFunctions );
			\Arg::_( $functionName )->assert( $condition, 'Function must not be an internal one.' );

			$instance = new self;
			$instance->isEvalCreated = false;
			$instance->functionName = $functionName;
			$isMethod = preg_match( "/^[\\w\\d_\\\\]+::[\\w\\d_]+$/", $functionName );
			if ( ! $isMethod && ! function_exists( $functionName ) ) {
				$namespace = self::hasnamespace( $functionName ) ? 'namespace ' . self::getnamespacefrom( $functionName ) . ";" : '';
				$functionName = self::hasNamespace( $functionName ) ? self::getFunctionNameFrom( $functionName ) : $functionName;
				$code = sprintf( '%sfunction %s(){return null;}', $namespace, $functionName );
				$ok = eval( $code );
				if ( $ok === false ) {
					throw new \Exception( "Could not eval code $code for function $functionName" );
				}
				$instance->isEvalCreated = true;
			}

			return $instance;
		}

		/**
		 * @param $functionName
		 *
		 * @return bool
		 */
		private static function hasNamespace( $functionName ) {
			$namespaceElements = explode( '\\', $functionName );
			if ( count( $namespaceElements ) === 1 ) {
				return false;
			}

			return true;
		}

		/**
		 * @param $functionName
		 *
		 * @return string
		 */
		private static function getNamespaceFrom( $functionName ) {
			$namespaceElements = explode( '\\', $functionName );
			array_pop( $namespaceElements );

			return implode( '\\', $namespaceElements );
		}

		private static function getFunctionNameFrom( $functionName ) {
			$elems = explode( '\\', $functionName );

			return array_pop( $elems );
		}

		public function getFunctionName() {
			return $this->functionName;
		}

		public function isEvalCreated() {
			return $this->isEvalCreated;
		}
	}
