<?php

namespace tad\FunctionMocker\Call\Verifier;

use PHPUnit_Framework_MockObject_Matcher_InvokedRecorder;
use tad\FunctionMocker\Call\CallHandlerInterface;
use tad\FunctionMocker\MatchingStrategy\MatchingStrategyFactory;
use tad\FunctionMocker\ReplacementRequest;

abstract class AbstractVerifier implements VerifierInterface, CallHandlerInterface
{
    /**
     * @var string The PHPUnit framework to use depending on the available version.
     */
    protected $framework;

    /**
     * @var string The PHPUnit constraint class to use depending on the available version.
     */
    protected $constraintClass;

    /**
     * @var PHPUnit_Framework_MockObject_Matcher_InvokedRecorder
     */
    protected $invokedRecorder;

    /**
     * @var ReplacementRequest
     */
    protected $request;

    public function __construct()
    {
        $this->framework = class_exists('PHPUnit_Framework_TestCase') ?
            'PHPUnit_Framework_TestCase'
            : '\\PHPUnit\\Framework\\TestCase';
        $this->constraintClass = class_exists('\PHPUnit_Framework_Constraint') ?
            '\PHPUnit_Framework_Constraint'
            : '\\PHPUnit\\Framework\\Constraint\\Constraint';
    }

    public function wasNotCalled()
    {
        $this->wasCalledTimes(0);
    }

    public function wasCalledTimes($times)
    {
        throw new \Exception('Method not implemented');
    }

    public function wasNotCalledWith(array $args)
    {
        $this->wasCalledWithTimes($args, 0);
    }

    public function wasCalledWithTimes(array $args, $times)
    {
        throw new \Exception('Method not implemented');
    }

    public function wasCalledOnce()
    {
        $this->wasCalledTimes(1);
    }

    public function wasCalledWithOnce(array $args)
    {
        $this->wasCalledWithTimes($args, 1);
    }

    /**
     * @param \PHPUnit_Framework_MockObject_Matcher_InvokedRecorder $invokedRecorder
     *
     * @return mixed
     */
    public function setInvokedRecorder(PHPUnit_Framework_MockObject_Matcher_InvokedRecorder $invokedRecorder)
    {
        $this->invokedRecorder = $invokedRecorder;
    }

    public function setRequest(ReplacementRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @param $times
     * @param $callTimes
     * @param $functionName
     *
     * @return mixed
     */
    protected function matchCallTimes($times, $callTimes, $functionName)
    {
        $matchingStrategy = MatchingStrategyFactory::make($times);
        /** @noinspection PhpUndefinedMethodInspection */
        $condition = $matchingStrategy->matches($callTimes);
        if (!$condition) {
            $message = sprintf('%s was called %d times, %s times expected.', $functionName, $callTimes, $times);
            $this->fail($message);
        }

        $this->assertTrue($condition);

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
    protected function matchCallWithTimes(array $args, $times, $functionName, $callTimes)
    {
        $matchingStrategy = MatchingStrategyFactory::make($times);
        /** @noinspection PhpUndefinedMethodInspection */
        $condition = $matchingStrategy->matches($callTimes);
        if (!$condition) {
            $printArgs = array_map(function ($arg) {
                return print_r($arg, true);
            }, $args);
            $args = "[\n\t" . implode(",\n\t", $printArgs) . ']';
            $message = sprintf('%s was called %d times with %s, %d times expected.', $functionName, $callTimes, $args, $times);
            $this->fail($message);
        }

        $this->assertTrue($condition);

        return $condition;
    }

    /**
     * @param $condition
     */
    protected function assertTrue($condition)
    {
        call_user_func([$this->framework, 'assertTrue'], $condition);
    }

    /**
     * @param string $message
     */
    protected function fail($message)
    {
        call_user_func([$this->framework, 'fail'], $message);
    }
}
