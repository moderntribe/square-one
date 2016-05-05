<?php

    namespace tad\FunctionMocker\Template;

    interface ClassTemplateInterface
    {
        public function getExtendedMockTemplate();

        public function getExtendedMethodTemplate($methodName);
    }