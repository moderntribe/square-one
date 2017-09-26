<?php

namespace tad\FunctionMocker\Template;


class VerifyingClassTemplate extends ClassTemplate
{
    public function getExtendedMockTemplate()
    {
        return <<< CODESET
class %%extendedClassName%% extends %%mockClassName%% implements %%interfaceName%% {

	public \$__functionMocker_callHandler;
	public \$__functionMocker_originalMockObject;
	public \$__functionMocker_invokedRecorder;
	public	\$__functionMocker_methodName = '';
	public	\$__functionMocker_methodArgs = [];

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
        $map = [
            'wasCalledTimes' => 'wasCalledTimesTemplate',
            'wasCalledWithTimes' => 'wasCalledWithTimesTemplate',
            'wasNotCalled' => 'wasNotCalledTemplate',
            'wasCalledOnce' => 'wasCalledOnceTemplate',
        ];
        if (array_key_exists($methodName, $map)) {
            return $this->{$map[$methodName]}();
        }
        return <<< CODESET
	public function %%call%%{
	    \$args = [func_get_args(),\$this->__functionMocker_methodName];
		call_user_func_array(array(\$this->__functionMocker_callHandler, '%%methodName%%'), \$args);
	}

CODESET;
    }

    protected function wasCalledTimesTemplate()
    {
        return <<< CODESET
		public function wasCalledTimes(\$times){
		if(empty(\$this->__functionMocker_methodArgs)){
		    \$args = [
		        \$times,
		        \$this->__functionMocker_methodName
		    ];
            \$call = 'wasCalledTimes';
		} else {
            \$args = [
                \$this->__functionMocker_methodArgs,
                \$times,
                \$this->__functionMocker_methodName
            ];
            \$call = 'wasCalledWithTimes';
		}
        call_user_func_array(array(\$this->__functionMocker_callHandler, \$call), \$args);
    }
CODESET;
    }

    protected function wasCalledWithTimesTemplate()
    {
        return <<< CODESET
        public function wasCalledWithTimes(array \$args = array(), \$times){
        \$args = [
            \$args,
            \$times,
            \$this->__functionMocker_methodName
        ];
        call_user_func_array(array(\$this->__functionMocker_callHandler, 'wasCalledWithTimes'), \$args);
    }
CODESET;

    }

    protected function wasNotCalledTemplate()
    {
        return <<< CODESET
        public function wasNotCalled(){
        if(empty(\$this->__functionMocker_methodArgs)){
            \$args = [
                \$this->__functionMocker_methodName
            ];
            \$call = 'wasNotCalled';
        } else {
            \$args = [
                \$this->__functionMocker_methodArgs,
                \$this->__functionMocker_methodName
            ];
            \$call = 'wasNotCalledWith';
        }
        call_user_func_array(array(\$this->__functionMocker_callHandler, \$call), \$args);
    }
CODESET;

    }

    protected function wasNotCalledWithTemplate()
    {
        return <<< CODESET
        public function wasNotCalledWith(array \$args = array()){
        \$args = [
            \$args,
            \$this->__functionMocker_methodName
        ];
        call_user_func_array(array(\$this->__functionMocker_callHandler, 'wasNotCalledWith'), \$args);
    }

CODESET;

    }

    protected function wasCalledWithOnceTemplate()
    {
        return <<< CODESET
        public function wasCalledWithOnce(array \$args = array()){
        \$args = [
            \$args,
            \$this->__functionMocker_methodName
        ];
        call_user_func_array(array(\$this->__functionMocker_callHandler, 'wasCalledWithOnce'), \$args);
    }
CODESET;
    }

    protected function wasCalledOnceTemplate()
    {
        return <<< CODESET
        public function wasCalledOnce(){
        if(empty(\$this->__functionMocker_methodArgs)){
            \$args = [
                \$this->__functionMocker_methodName
            ];
            \$call = 'wasCalledOnce';
        }else{
            \$args = [
                \$this->__functionMocker_methodArgs,
                \$this->__functionMocker_methodName
            ];
            \$call = 'wasCalledWithOnce';
        }
        call_user_func_array(array(\$this->__functionMocker_callHandler, \$call), \$args);
    }
CODESET;

    }
}
