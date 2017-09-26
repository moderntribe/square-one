<?php

namespace tad\FunctionMocker\Call\Verifier;

use tad\FunctionMocker\Call\Logger\LoggerInterface;
use tad\FunctionMocker\Checker;
use tad\FunctionMocker\ReplacementRequest;
use tad\FunctionMocker\ReturnValue;

class CallVerifierFactory
{

    public static function make(ReplacementRequest $request, Checker $checker, ReturnValue $returnValue, LoggerInterface $callLogger)
    {
        if ($request->isFunction()) {
            return FunctionCallVerifier::__from($checker, $returnValue, $callLogger);
        }
        if ($request->isStaticMethod()) {
            return StaticMethodCallVerifier::__from($checker, $returnValue, $callLogger);
        }

        return InstanceMethodCallVerifier::from($returnValue, $callLogger);
    }
}
