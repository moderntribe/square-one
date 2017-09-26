<?php

namespace tad\FunctionMocker;

class ReplacementRequest
{

	/**
	 * @var string
	 */
	public $methodName;

	/**
	 * @var bool
	 */
	protected $isFunction;

	/**
	 * @var bool
	 */
	protected $isMethod;

	/**
	 * @var bool
	 */
	protected $isStaticMethod;

	/**
	 * @var bool
	 */
	protected $isInstanceMethod;

	/**
	 * @var string
	 */
	protected $requestClassName;

	/**
	 * @var bool
	 */
	protected $isClass;

	public static function on($mockRequest)
	{
		\Arg::_($mockRequest, 'Function or method name')->is_string();

		$type = self::getType($mockRequest);

		return self::getInstanceForTypeAndRequest($type, $mockRequest);
	}

	/**
	 * @param $mockRequest
	 *
	 * @return string
	 */
	private static function getType($mockRequest)
	{
		if (class_exists($mockRequest) || interface_exists($mockRequest) || trait_exists($mockRequest)) {
			return 'class';
		}
		if (preg_match("/^[\\w\\\\_]*(::|->)[\\w\\d_]+/um", $mockRequest)) {
			return 'method';
		}

		return 'function';
	}

	/**
	 * @param $type
	 * @param $mockRequest
	 *
	 * @return ReplacementRequest
	 */
	private static function getInstanceForTypeAndRequest($type, $mockRequest)
	{
		$instance = new self;
		switch ($type) {
			case 'class': {
				$instance->isFunction = false;
				$instance->isClass = true;
				$instance->isStaticMethod = false;
				$instance->isMethod = false;
				$instance->isInstanceMethod = false;
				$instance->requestClassName = $mockRequest;
				$instance->methodName = false;
				break;
			}
			case 'method': {
				$request = preg_split('/(::|->)/', $mockRequest);
				$className = $request[0];
				$methodName = $request[1];
				$reflection = new \ReflectionMethod($className, $methodName);

				$instance->isFunction = false;
				$instance->isClass = false;
				$instance->isMethod = true;
				$instance->isInstanceMethod = !$reflection->isStatic();

				$instance->ensure_matching_symbol($mockRequest);

				$instance->isStaticMethod = $reflection->isStatic();
				$instance->requestClassName = $reflection->class;
				$instance->methodName = $reflection->name;
				break;
			}
			case 'function': {
				$instance->isFunction = true;
				$instance->isClass = false;
				$instance->isMethod = false;
				$instance->isStaticMethod = false;
				$instance->isInstanceMethod = false;
				$instance->requestClassName = '';
				$instance->methodName = $mockRequest;
				break;
			}
		}

		return $instance;
	}

	/**
	 * @param $requestString
	 */
	private function ensure_matching_symbol($requestString)
	{
		$m = [];
		preg_match('/(::|->)/', $requestString, $m);
		$symbol = $m[1];
		if ($symbol === '->' && !$this->isInstanceMethod()) {
			throw new \InvalidArgumentException('Request was for a static method but the \'->\' symbol was used; keep it clear.');
		}
	}

	public function isInstanceMethod()
	{
		return $this->isMethod && $this->isInstanceMethod;
	}

	public function isFunction()
	{
		return $this->isFunction;
	}

	public function isStaticMethod()
	{
		return $this->isMethod && $this->isStaticMethod;
	}

	public function isMethod()
	{
		return $this->isMethod;
	}

	public function getClassName()
	{
		return $this->requestClassName;
	}

	public function getMethodName()
	{
		return $this->methodName;
	}

	public function isClass()
	{
		return $this->isClass;
	}
}
