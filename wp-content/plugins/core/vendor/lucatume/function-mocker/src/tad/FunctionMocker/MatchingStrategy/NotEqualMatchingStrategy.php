<?php

	namespace tad\FunctionMocker\MatchingStrategy;

	class NotEqualMatchingStrategy extends AbstractMatchingStrategy {

		public function matches( $times ) {
			return $times !== $this->times;
		}

		public function __toString(){
			return sprintf('any number but not %d', $this->times);
		}
	}
