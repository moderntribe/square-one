<?php

	namespace tad\FunctionMocker\Template\Extender;


	class SpyExtender extends AbstractExtender {

		protected $extenderClassName     = 'tad\FunctionMocker\Call\Verifier\InstanceMethodCallVerifier';
		protected $extenderInterfaceName = 'tad\FunctionMocker\Call\Verifier\VerifierInterface';

		public function getExtendedMethodCallsAndNames() {
			return array(
				'wasCalledTimes($times)' => 'wasCalledTimes',
				'wasCalledWithTimes(array $args = array(), $times)' => 'wasCalledWithTimes',
				'wasNotCalled()' => 'wasNotCalled',
				'wasNotCalledWith(array $args = array())' => 'wasNotCalledWith',
				'wasCalledWithOnce(array $args = array())' => 'wasCalledWithOnce',
				'wasCalledOnce()' => 'wasCalledOnce'
			);
		}

	}
