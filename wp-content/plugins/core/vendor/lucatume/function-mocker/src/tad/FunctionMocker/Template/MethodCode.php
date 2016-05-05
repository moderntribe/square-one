<?php

    namespace tad\FunctionMocker\Template;

    class MethodCode implements MethodCodeInterface
    {

        /**
         * @var string
         */
        protected $targetClass;

        /**
         * @var \ReflectionClass
         */
        protected $reflection;

        /**
         * @var array
         */
        protected $methods;

        /** @var  string */
        protected $contents;

        public function setTargetClass($targetClass)
        {
            $this->targetClass = $targetClass;
            $this->reflection = new \ReflectionClass($targetClass);
            $this->methods = $this->reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
            $fileName = $this->reflection->getFileName();
            if (file_exists($fileName)) {
                $this->contents = file_get_contents($fileName);
            }

            return $this;
        }

        public function getTemplateFrom($methodName)
        {
            $body = '%%pre%% %%body%% %%post%%';

            return $this->getMethodCodeForWithBody($methodName, $body);
        }

        /**
         * @param $methodName
         *
         * @param $body
         *
         * @return array|mixed|string
         */
        protected function getMethodCodeForWithBody($methodName, $body)
        {
            $code = $this->getMethodCode($methodName);

            $code = $this->replaceBody($body, $code);

            return $code;
        }

        /**
         * @param $methodName
         *
         * @return array|string
         */
        protected function getMethodCode($methodName)
        {
            $method = is_a($methodName, '\ReflectionMethod') ? $methodName : new \ReflectionMethod($this->targetClass, $methodName);

            $declaringClass = $method->getDeclaringClass();
            $notTargetClass = $declaringClass->name != $this->targetClass;
            if ($notTargetClass) {
                $method = new \ReflectionMethod($declaringClass->name, $methodName);
                $contents = file_get_contents($method->getFileName());
            } else {
                $contents = $this->contents;
            }

            $startLine = $method->getStartLine();
            $endLine = $method->getEndLine();

            $classAliases = [];
            $lines = explode(PHP_EOL, $contents);
            foreach ($lines as $line) {
                $frags = explode(' ', $line);
                if (!empty($frags) && $frags[0] == 'use') {
                    $fullClassName = $frags[1];
                    // use Acme\Class as Alias
                    if (count($frags) > 2) {
                        $alias = $frags[3];
                    } else {
                        if (strpos($frags[1], '\\')) {
                            $classNameFrags = explode('\\', $frags[1]);
                            $alias = array_pop($classNameFrags);
                        } else {
                            $alias = $frags[1];
                        }
                    }
                    $alias = trim($alias, ';');
                    $classAliases[$alias] = trim($fullClassName, ';');
                }
            }

            $lines = array_map(function ($line) use ($classAliases) {
                foreach ($classAliases as $classAlias => $fullClassName) {
                    $line = str_replace($classAlias, $fullClassName, $line);
                }
                return trim($line);
            }, $lines);

            $code = array_splice($lines, $startLine - 1, $endLine - $startLine + 1);

            $code[0] = preg_replace('/\\s*abstract\\s*/', '', $code[0]);

            $code = implode(" ", $code);

            return $code;
        }

        /**
         * @param $body
         * @param $code
         *
         * @return mixed
         */
        protected function replaceBody($body, $code)
        {
            $code = preg_replace('/\\{.*\\}$|;$/', '{' . $body . '}', $code);
            $code = preg_replace('/\\(\\s+/', '(', $code);
            $code = preg_replace('/\\s+\\)/', ')', $code);

            return $code;
        }

        public function getAllMockCallings()
        {
            $code = array_map(function ($method) {
                return $this->getMockCallingFrom($method);
            }, $this->methods);
            $code = implode("\n\n\t", $code);

            return $code;
        }

        public function getMockCallingFrom($methodName)
        {
            $method = is_a($methodName, '\ReflectionMethod') ? $methodName : new \ReflectionMethod($this->targetClass, $methodName);
            $methodName = is_string($methodName) ? $methodName : $method->name;
            $args = array_map(function (\ReflectionParameter $parameter) {
                return '$' . $parameter->name;
            }, $method->getParameters());
            $args = implode(', ', $args);
            $body = $this->getCallBody($methodName, $args);

            return $this->getMethodCodeForWithBody($methodName, $body);
        }

        /**
         * @param $methodName
         * @param $args
         * @return string
         */
        protected function getCallBody($methodName, $args)
        {
            $body = "return \$this->__functionMocker_originalMockObject->$methodName($args);";
            return $body;
        }
    }
