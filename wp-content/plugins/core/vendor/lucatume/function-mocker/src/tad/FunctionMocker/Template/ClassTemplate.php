<?php

	namespace tad\FunctionMocker\Template;

	class ClassTemplate implements ClassTemplateInterface
	{

		public function getExtendedMockTemplate()
		{
			return <<< CODESET
class %%extendedClassName%% extends %%mockClassName%% implements %%interfaceName%% {

	public \$__functionMocker_callHandler;
	public \$__functionMocker_originalMockObject;
	public \$__functionMocker_invokedRecorder;

	public function __set_functionMocker_callHandler(tad\FunctionMocker\Call\CallHandlerInterface \$callHandler){
		\$this->__functionMocker_callHandler = \$callHandler;
	}

	public function __get_functionMocker_CallHandler(){
		return \$this->__functionMocker_callHandler;
	}

	public function __set_functionMocker_originalMockObject(\PHPUnit_Framework_MockObject_MockObject \$mockObject){
		\$this->__functionMocker_originalMockObject = \$mockObject;
	}

	public function __set_functionMocker_invokedRecorder(\PHPUnit_Framework_MockObject_Matcher_InvokedRecorder \$invokedRecorder){
		\$this->__functionMocker_invokedRecorder = \$invokedRecorder;
	}

	public function __get_functionMocker_invokedRecorder(){
		return \$this->__functionMocker_invokedRecorder;
	}

	%%extendedMethods%%

	%%originalMethods%%
}
CODESET;
		}

		public function getExtendedMethodTemplate($methodName)
		{
			return <<< CODESET
	public function %%call%%{
		call_user_func_array(array(\$this->__functionMocker_callHandler, '%%methodName%%'), func_get_args());
		return \$this;
	}

CODESET;

		}
	}
