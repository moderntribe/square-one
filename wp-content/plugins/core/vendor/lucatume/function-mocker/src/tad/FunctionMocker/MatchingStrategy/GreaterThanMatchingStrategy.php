<?php

	namespace tad\FunctionMocker\MatchingStrategy;


	class GreaterThanMatchingStrategy extends AbstractMatchingStrategy {

		public function matches( $times ) {
			return $times > $this->times;
		}

		public function __toString() {
			return sprintf( 'more than %d', $this->times );
		}
	}
