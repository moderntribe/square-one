<?php

namespace tad\FunctionMocker\Method;


use tad\FunctionMocker\Call\Logger\LoggerInterface;
use tad\FunctionMocker\Call\Verifier\InstanceMethodCallVerifier;
use tad\FunctionMocker\ReturnValue;

class Verifier
{

    /**
     * @var ReturnValue
     */
    protected $returnValue;

    /**
     * @var LoggerInterface
     */
    protected $callLogger;

    /**
     * Verifier constructor.
     */
    public function __construct()
    {
    }

    public function __call($name, array $args = null)
    {
        return InstanceMethodCallVerifier::from($this->returnValue, $this->callLogger);
    }
}