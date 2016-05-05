<?php

	namespace tad\FunctionMocker\Call\Verifier;

	use PHPUnit_Framework_MockObject_Matcher_InvokedRecorder;
	use tad\FunctionMocker\Call\CallHandlerInterface;
	use tad\FunctionMocker\MatchingStrategy\MatchingStrategyFactory;
	use tad\FunctionMocker\ReplacementRequest;

	abstract class AbstractVerifier implements VerifierInterface, CallHandlerInterface {

		/**
		 * @var PHPUnit_Framework_MockObject_Matcher_InvokedRecorder
		 */
		protected $invokedRecorder;

		/**
		 * @var ReplacementRequest
		 */
		protected $request;

		public function wasCalledTimes( $times ) {
			throw new \Exception( 'Method not implemented' );
		}

		public function wasCalledWithTimes( array $args, $times ) {
			throw new \Exception( 'Method not implemented' );
		}

		public function wasNotCalled() {
			$this->wasCalledTimes( 0 );
		}

		public function wasNotCalledWith( array $args ) {
			$this->wasCalledWithTimes( $args, 0 );
		}

		public function wasCalledOnce() {
			$this->wasCalledTimes( 1 );
		}

		public function wasCalledWithOnce( array $args ) {
			$this->wasCalledWithTimes( $args, 1 );
		}

		/**
		 * @param \PHPUnit_Framework_MockObject_Matcher_InvokedRecorder $invokedRecorder
		 *
		 * @return mixed
		 */
		public function setInvokedRecorder( PHPUnit_Framework_MockObject_Matcher_InvokedRecorder $invokedRecorder ) {
			$this->invokedRecorder = $invokedRecorder;
		}

		public function setRequest( ReplacementRequest $request ) {
			$this->request = $request;
		}

		/**
		 * @param $times
		 * @param $callTimes
		 * @param $functionName
		 *
		 * @return mixed
		 */
		protected function matchCallTimes( $times, $callTimes, $functionName ) {
			$matchingStrategy = MatchingStrategyFactory::make( $times );
			/** @noinspection PhpUndefinedMethodInspection */
			$condition        = $matchingStrategy->matches( $callTimes );
			if ( ! $condition ) {
				$message = sprintf( '%s was called %d times, %s times expected.', $functionName, $callTimes, $times );
				\PHPUnit_Framework_Assert::fail( $message );
			}

			\PHPUnit_Framework_Assert::assertTrue( $condition );

			return $condition;
		}

		/**
		 * @param array $args
		 * @param       $times
		 * @param       $functionName
		 * @param       $callTimes
		 *
		 * @return mixed
		 */
		protected function matchCallWithTimes( array $args, $times, $functionName, $callTimes ) {
			$matchingStrategy = MatchingStrategyFactory::make( $times );
			/** @noinspection PhpUndefinedMethodInspection */
			$condition        = $matchingStrategy->matches( $callTimes );
            if ( ! $condition ) {
                $printArgs = array_map( function( $arg ) {
                    return print_r( $arg, true );
                }, $args);
				$args    = "[\n\t" . implode( ",\n\t", $printArgs ) . ']';
				$message = sprintf( '%s was called %d times with %s, %d times expected.', $functionName, $callTimes, $args, $times );
				\PHPUnit_Framework_Assert::fail( $message );
			}

			\PHPUnit_Framework_Assert::assertTrue( $condition );

			return $condition;
		}
	}
