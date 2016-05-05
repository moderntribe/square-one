<?php

	namespace tad\FunctionMocker\MatchingStrategy;


	Abstract class AbstractMatchingStrategy implements MatchingStrategyInterface {

		protected $times;

		public static function on( $times ) {
			\Arg::_( $times, 'Times' )->is_int();

			$instance        = new static();
			$instance->times = $times;

			return $instance;
		}

		public function matches( $times ) {
			throw new \RuntimeException('Method is not defined');
		}

		public function __toString(){
			throw new \RuntimeException('Method is not defined');
		}
	}
