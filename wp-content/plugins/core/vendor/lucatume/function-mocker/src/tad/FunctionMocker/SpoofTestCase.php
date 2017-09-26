<?php

namespace tad\FunctionMocker;

if (class_exists('PHPUnit_Framework_TestCase') && !class_exists('\\PHPunit\\Framework\\TestCase')) {
	// PHPUnit < 6.0 support
	class SpoofTestCase extends \PHPUnit_Framework_TestCase {

	}
} else {
	class SpoofTestCase extends \PHPunit\Framework\TestCase {

	}
}
