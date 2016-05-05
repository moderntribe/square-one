<?php
	/**
	 * Created by PhpStorm.
	 * User: Luca
	 * Date: 20/11/14
	 * Time: 17:21
	 */

	namespace tad\FunctionMocker\Template\Extender;


	interface ExtenderInterface {

		public function getExtenderClassName();
		public function getExtendedMethodCallsAndNames();
		public function getExtenderInterfaceName();
	}
