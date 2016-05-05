<?php
	namespace tad\FunctionMocker\Call\Logger;


	interface LoggerInterface {

		public function called( array $args = null );
	}
