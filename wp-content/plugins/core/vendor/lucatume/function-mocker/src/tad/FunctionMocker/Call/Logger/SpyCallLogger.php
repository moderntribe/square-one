<?php

namespace tad\FunctionMocker\Call\Logger;


use tad\FunctionMocker\CallTrace;

class SpyCallLogger implements LoggerInterface
{

    protected $calls = array();

    public function called(array $args = null)
    {
        $this->calls[] = CallTrace::fromArguments($args);
    }

    public function getCallTimes(array $args = null)
    {
        $calls = $this->calls;
        if ($args) {
            $calls = $this->getCallsMatchingArgs($args, $calls);
        }

        return count($calls);
    }

    /**
     * @param array $args
     * @param $calls
     * @return array
     */
    private function getCallsMatchingArgs(array $args, $calls)
    {
        $calls = array_filter($calls, function (CallTrace $call) use ($args) {
            $callArgs = $call->getArguments();

            return $this->compareArgs($args, $callArgs);
        });
        return $calls;
    }

    /**
     * @param $args
     * @param $callArgs
     * @return bool
     */
    private function compareArgs(array $args, array $callArgs)
    {
        if (count($args) > count($callArgs)) {
            return false;
        }
        $args_count = count($args);
        for ($i = 0; $i < $args_count; $i++) {
            $arg = $args[$i];
            $callArg = $callArgs[$i];
            if (!$this->compareArg($arg, $callArg)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $arg
     * @param $callArg
     * @return bool
     */
    private function compareArg($arg, $callArg)
    {
        return is_a($arg, '\PHPUnit_Framework_Constraint') ? $arg->evaluate($callArg, '', true) : $arg === $callArg;
    }
}
