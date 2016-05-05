<?php

	namespace tad\FunctionMocker\Call\Verifier;

	use tad\FunctionMocker\Call\Logger\LoggerInterface;
	use tad\FunctionMocker\Checker;
	use tad\FunctionMocker\ReturnValue;

	class FunctionCallVerifier extends AbstractVerifier {

		/**
		 * @var Checker
		 */
		protected $__checker;

		/** @var  ReturnValue */
		protected $__returnValue;

		/**
		 * @var LoggerInterface
		 */
		protected $__callLogger;

		public static function __from( Checker $checker, ReturnValue $returnValue, LoggerInterface $callLogger ) {
			$instance                = new static;
			$instance->__checker     = $checker;
			$instance->__returnValue = $returnValue;
			$instance->__callLogger  = $callLogger;

			return $instance;
		}

		public function __willReturnCallable() {
			return $this->__returnValue->isCallable();
		}

		public function __wasEvalCreated() {
			return $this->__checker->isEvalCreated();
		}

		public function __getFunctionName() {
			return $this->__checker->getFunctionName();
		}

		/**
		 * Checks if the function or method was called the specified number
		 * of times.
		 *
		 * @param  int $times
		 *
		 * @return void
		 */
		public function wasCalledTimes( $times ) {

			/** @noinspection PhpUndefinedMethodInspection */
			$callTimes    = $this->__callLogger->getCallTimes();
			$functionName = $this->__getFunctionName();

			$this->matchCallTimes( $times, $callTimes, $functionName );
		}

		/**
		 * Checks if the function or method was called with the specified
		 * arguments a number of times.
		 *
		 * @param  array $args
		 * @param  int   $times
		 *
		 * @return void
		 */
		public function wasCalledWithTimes( array $args, $times ) {

			/** @noinspection PhpUndefinedMethodInspection */
			$callTimes    = $this->__callLogger->getCallTimes( $args );
			$functionName = $this->__getFunctionName();

			$this->matchCallWithTimes( $args, $times, $functionName, $callTimes );
		}
	}
