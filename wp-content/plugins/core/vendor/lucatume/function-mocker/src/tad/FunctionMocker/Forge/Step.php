<?php
namespace tad\FunctionMocker\Forge;


use tad\FunctionMocker\ReplacementRequest;
use tad\FunctionMocker\Replacers\InstanceForger;
use tad\FunctionMocker\ReturnValue;

class Step implements StepInterface
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var InstanceForger
     */
    protected $instanceForger;

    /**
     * @var string[]
     */
    protected $methods = [];

    protected $mockObject;

    /**
     * @var ReplacementRequest
     */
    protected $request;

	/**
	 * Step constructor.
	 */
	public function __construct() {}

    public static function instance($class)
    {
        \Arg::_($class, 'Class name')
            ->is_string()
            ->assert(class_exists($class), 'Class to getMock must be defined');

        $instance = new self;;
        $instance->class = $class;

        return $instance;
    }

    public function get()
    {
        $this->setUpMockObject();

        return $this->instanceForger->getWrappedMockObject($this->mockObject, $this->class, $this->request);
    }

    protected function setUpMockObject()
    {
        if (empty($this->mockObject)) {
            $this->mockObject = $this->getForgedMockObject();
            $this->setMockObjectExpectation($this->mockObject);
            $this->request = InstanceMethodRequest::instance($this->class);
        }
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getForgedMockObject()
    {
        $methods = array_merge(array_keys($this->methods), ['__construct']);
        $mockObject = $this->instanceForger->getPHPUnitMockObjectFor($this->class, $methods);
        return $mockObject;
    }

    /**
     * @param $mockObject
     */
    protected function setMockObjectExpectation(&$mockObject)
    {
        foreach ($this->methods as $method => $returnValue) {
            $this->instanceForger->setMockObjectExpectation($mockObject, $method, ReturnValue::from($returnValue));
        }
    }

    /**
     * @param InstanceForger $instanceForger
     */
    public function setInstanceForger(InstanceForger $instanceForger)
    {
        $this->instanceForger = $instanceForger;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function method($methodName, $returnValue = null)
    {
        $this->methods[$methodName] = $returnValue;

        return $this;
    }

    public function verify()
    {
        $this->setUpMockObject();

        return $this->instanceForger->getVerifyingMockObject($this->mockObject, $this->class, $this->request);
    }
}