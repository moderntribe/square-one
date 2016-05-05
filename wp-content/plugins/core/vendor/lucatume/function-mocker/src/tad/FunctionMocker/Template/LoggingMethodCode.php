<?php

    namespace tad\FunctionMocker\Template;


    class LoggingMethodCode extends MethodCode
    {
        protected function getCallBody($methodName, $args)
        {
            $body = <<< CODESET

            \$this->__functionMocker_methodName = '$methodName';
            \$this->__functionMocker_methodArgs = func_get_args();

            return \$this;
CODESET;

            return $body;
        }

    }