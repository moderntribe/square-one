<?php

namespace tad\FunctionMocker\Replacers;


use tad\FunctionMocker\MockWrapper;
use tad\FunctionMocker\ReplacementRequest;
use tad\FunctionMocker\ReturnValue;
use tad\FunctionMocker\Template\ClassTemplate;
use tad\FunctionMocker\Template\LoggingMethodCode;
use tad\FunctionMocker\Template\MethodCode;
use tad\FunctionMocker\Template\VerifyingClassTemplate;

class InstanceForger
{

    /**
     * @var \PHPUnit_Framework_MockObject_Matcher_InvokedRecorder
     */
    protected $invokedRecorder;

    /**
     * @var array
     */
    protected $wrappedMockObjectsCache = [];

    /**
     * @var \PHPUnit_Framework_TestCase|\PHPUnit\Framework\TestCase
     */
    private $testCase;

    public function getMock(ReplacementRequest $request, ReturnValue $returnValue)
    {
        $className = $request->getClassName();
        $methodName = $request->getMethodName();

        $methods = ['__construct', $methodName];

        $mockObject = $this->getPHPUnitMockObjectFor($className, $methods);
        $this->setMockObjectExpectation($mockObject, $methodName, $returnValue);

        $wrapperInstance = $this->getWrappedMockObject($mockObject, $className, $request);

        return $wrapperInstance;
    }

    public function getPHPUnitMockObjectFor($className, array $methods)
    {
        $rc = new \ReflectionClass($className);

        $mockObject = null;

        if ($rc->isInterface()) {
            $mockObject = $this->testCase->getMockBuilder($className)
                ->getMock();
        } elseif ($rc->isAbstract()) {
            $mockObject = $this->testCase->getMockBuilder($className)->disableOriginalConstructor()
                ->setMethods($methods)->getMockForAbstractClass();
        } elseif ($rc->isTrait()) {
            $mockObject = $this->testCase->getMockBuilder($className)->disableOriginalConstructor()
                ->setMethods($methods)->getMockForTrait();
        } else {
			$mockBuilder = $this->testCase->getMockBuilder($className)
				->disableOriginalConstructor()
				->disableOriginalClone()
				->disableArgumentCloning();

			// removed in later versions of PHPUnit
			if (method_exists($mockBuilder, 'disallowMockingUnknownTypes')) {
				$mockBuilder->disallowMockingUnknownTypes();
			}

			$mockObject = $mockBuilder->getMock();
        }

        return $mockObject;
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $mockObject
     * @param $methodName
     * @param ReturnValue|null $returnValue
     */
    public function setMockObjectExpectation(&$mockObject, $methodName, ReturnValue $returnValue = null)
    {
        if ($returnValue->isCallable()) {
            // callback
            $mockObject->expects($this->invokedRecorder)->method($methodName)
                ->willReturnCallback($returnValue->getValue());
        } else if ($returnValue->isSelf()) {
            // ->
            $mockObject->expects($this->invokedRecorder)->method($methodName)->willReturn($mockObject);
        } else {
            // value
            $mockObject->expects($this->invokedRecorder)->method($methodName)
                ->willReturn($returnValue->getValue());
        }
    }

    /**
     * @param $mockObject
     * @param $className
     * @param ReplacementRequest $request
     * @param bool $verifying The type of wrapping to return.
     *
     * @return mixed
     */
    public function getWrappedMockObject($mockObject, $className, ReplacementRequest $request, $verifying = false)
    {
        $hash = spl_object_hash($mockObject);

        $wrapperInstance = null;

        if ($verifying && isset($this->wrappedMockObjectsCache[$hash])) {
            return $this->wrappedMockObjectsCache[$hash];
        }

        $classTemplate = $verifying ? new VerifyingClassTemplate() : new ClassTemplate();
        $methodCode = $verifying ? new LoggingMethodCode() : new MethodCode();
        $mockWrapper = new MockWrapper($className, $classTemplate, $methodCode);
        $wrapperInstance = $mockWrapper->wrap($mockObject, $this->invokedRecorder, $request);

        if ($verifying) {
            $this->wrappedMockObjectsCache[$hash] = $wrapperInstance;
        }

        return $wrapperInstance;
    }

    /**
     * @param \PHPUnit_Framework_TestCase|\PHPUnit\Framework\TestCase $testCase
     */
    public function setTestCase($testCase)
    {
        $this->testCase = $testCase;
        $this->invokedRecorder = $this->testCase->any();
    }

    public function getVerifyingMockObject($mockObject, $class, $request)
    {
        return $this->getWrappedMockObject($mockObject, $class, $request, true);
    }
}