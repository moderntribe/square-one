<?php

namespace tad\FunctionMocker\Tests;

if (class_exists('\\PHPUnit_Framework_TestCase')) {
    class TestCase extends \PHPUnit_Framework_TestCase
    {
        protected function expectFailure()
        {
            $this->expectException(\PHPUnit_Framework_AssertionFailedError::class);
        }
    }
} else {
    class TestCase extends \PHPUnit\Framework\TestCase
    {
        protected function expectFailure()
        {
            $this->expectException(\PHPUnit\Framework\AssertionFailedError::class);
        }
    }
}