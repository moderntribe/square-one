<?php

	namespace tad\FunctionMocker\MatchingStrategy;


	class AtLeastMatchingStrategy extends AbstractMatchingStrategy {

		public function matches( $times ) {
			return $times >= $this->times;
		}

		public function __toString() {
			return sprintf( 'at least %d', $this->times );
		}
	}
