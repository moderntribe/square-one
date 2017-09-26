<?php

namespace tad\FunctionMocker;

use tad\FunctionMocker\Template\ClassTemplate;
use tad\FunctionMocker\Template\ClassTemplateInterface;
use tad\FunctionMocker\Template\Extender\ExtenderInterface;
use tad\FunctionMocker\Template\Extender\SpyExtender;
use tad\FunctionMocker\Template\MethodCode;
use tad\FunctionMocker\Template\MethodCodeInterface;

class MockWrapper
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $wrappedObject;

    /**
     * @var string
     */
    protected $originalClassName;

    /**
     * @var ClassTemplateInterface
     */
    protected $classTemplate;

    public function __construct($originalClassName = '', ClassTemplateInterface $classTemplate = null, MethodCodeInterface $methodCode = null)
    {
        $this->originalClassName = $originalClassName;
        $this->classTemplate = $classTemplate ?: new ClassTemplate();
        $this->methodCode = $methodCode ?: new MethodCode();
    }

    public function getWrappedObject()
    {
        return $this->wrappedObject;
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|MockObject $mockObject
     * @param \PHPUnit_Framework_MockObject_Matcher_InvokedRecorder $invokedRecorder
     * @param ReplacementRequest $request
     * @return mixed
     */
    public function wrap(\PHPUnit_Framework_MockObject_MockObject $mockObject, \PHPUnit_Framework_MockObject_Matcher_InvokedRecorder $invokedRecorder, ReplacementRequest $request)
    {

        $extender = new SpyExtender();

        return $this->getWrappedInstance($mockObject, $extender, $invokedRecorder, $request);
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $object
     * @param                                                       $extender
     *
     * @param \PHPUnit_Framework_MockObject_Matcher_InvokedRecorder $invokedRecorder
     * @param ReplacementRequest $request
     *
     * @throws \Exception
     *
     * @return mixed
     */
    protected function getWrappedInstance(\PHPUnit_Framework_MockObject_MockObject $object, ExtenderInterface $extender, \PHPUnit_Framework_MockObject_Matcher_InvokedRecorder $invokedRecorder = null, ReplacementRequest $request = null)
    {
        $mockClassName = get_class($object);
        $extendClassName = sprintf('%s_%s', uniqid('Extended_'), $mockClassName);
        /** @noinspection PhpUndefinedMethodInspection */
        $extenderClassName = $extender->getExtenderClassName();

        if (!class_exists($extendClassName)) {
            $classTemplate = $this->classTemplate;
            $template = $classTemplate->getExtendedMockTemplate();

            /** @noinspection PhpUndefinedMethodInspection */
            $interfaceName = $extender->getExtenderInterfaceName();
            /** @noinspection PhpUndefinedMethodInspection */
            $extendedMethods = $extender->getExtendedMethodCallsAndNames();

            $extendedMethodsCode = array();
            array_walk($extendedMethods, function ($methodName, $call) use (&$extendedMethodsCode, $classTemplate) {
                $methodCodeTemplate = $classTemplate->getExtendedMethodTemplate($methodName);
                $code = preg_replace('/%%methodName%%/', $methodName, $methodCodeTemplate);
                $code = preg_replace('/%%call%%/', $call, $code);
                $extendedMethodsCode[] = $code;
            });
            $extendedMethodsCode = implode("\n", $extendedMethodsCode);

            $methodCode = $this->methodCode;
            $methodCode->setTargetClass($this->originalClassName);
            $originalMethodsCode = $methodCode->getAllMockCallings();

            $classCode = preg_replace('/%%extendedClassName%%/', $extendClassName, $template);
            $classCode = preg_replace('/%%mockClassName%%/', $mockClassName, $classCode);
            $classCode = preg_replace('/%%interfaceName%%/', $interfaceName, $classCode);
            $classCode = preg_replace('/%%extenderClassName%%/', $extenderClassName, $classCode);
            $classCode = preg_replace('/%%extendedMethods%%/', $extendedMethodsCode, $classCode);
            $classCode = preg_replace('/%%originalMethods%%/', $originalMethodsCode, $classCode);

            $ok = eval($classCode);

            if ($ok === false) {
                throw new \Exception('There was a problem evaluating the code');
            }
        }

        $reflectionClass = new \ReflectionClass($extendClassName);
        $wrapperInstance = $reflectionClass->newInstanceWithoutConstructor();

        /** @noinspection PhpUndefinedMethodInspection */
        $wrapperInstance->__set_functionMocker_originalMockObject($object);
        $callHandler = new $extenderClassName;
        if ($invokedRecorder) {
            /** @noinspection PhpUndefinedMethodInspection */
            $callHandler->setInvokedRecorder($invokedRecorder);
            /** @noinspection PhpUndefinedMethodInspection */
            $wrapperInstance->__set_functionMocker_invokedRecorder($invokedRecorder);
        }
        if ($request) {
            /** @noinspection PhpUndefinedMethodInspection */
            $callHandler->setRequest($request);
        }
        /** @noinspection PhpUndefinedMethodInspection */
        $wrapperInstance->__set_functionMocker_callHandler($callHandler);

        return $wrapperInstance;
    }

    public function setOriginalClassName($className)
    {
        \Arg::_($className, "Original class name")->is_string()
            ->assert(class_exists($className) || interface_exists($className) || trait_exists($className), 'Original class, interface or trait must be defined');

        $this->originalClassName = $className;
    }

    public function setClassTemplate(ClassTemplateInterface $classTemplate)
    {
        $this->classTemplate = $classTemplate;
    }
}
