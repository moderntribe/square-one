<?php

namespace tad\FunctionMocker\Call;


use PHPUnit_Framework_MockObject_Matcher_InvokedRecorder;
use tad\FunctionMocker\ReplacementRequest;

interface CallHandlerInterface
{

    /**
     * @param \PHPUnit_Framework_MockObject_Matcher_InvokedRecorder $invokedRecorder
     *
     * @return mixed
     */
    public function setInvokedRecorder(PHPUnit_Framework_MockObject_Matcher_InvokedRecorder $invokedRecorder);

    /**
     * @param ReplacementRequest $request
     *
     * @return mixed
     */
    public function setRequest(ReplacementRequest $request);
}
