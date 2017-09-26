<?php

namespace tad\FunctionMocker\Template;

interface MethodCodeInterface
{
    public function setTargetClass($targetClass);

    public function getTemplateFrom($methodName);

    public function getAllMockCallings();

    public function getMockCallingFrom($methodName);
}