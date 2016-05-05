<?php

    namespace tad\FunctionMocker;

    use tad\FunctionMocker\Call\Logger\CallLoggerFactory;
    use tad\FunctionMocker\Call\Logger\LoggerInterface;
    use tad\FunctionMocker\Call\Verifier\CallVerifierFactory;
    use tad\FunctionMocker\Call\Verifier\FunctionCallVerifier;
    use tad\FunctionMocker\Forge\Step;
    use tad\FunctionMocker\Replacers\InstanceForger;

    class FunctionMocker
    {
        // allows wrapping assert methods
        use PHPUnitFrameworkAssertWrapper;

        /**
         * @var \PHPUnit_Framework_TestCase
         */
        protected static $testCase;

        /** @var  array */
        protected static $defaultWhitelist = array(
            'vendor/antecedent'
        );

        protected static $defaultBlacklist = array(
            'vendor/codeception',
            'vendor/phpunit',
            'vendor/phpspec'
        );
        /**
         * Stores the previous values of each global replaced.
         *
         * @var array
         */
        protected static $globalsBackup = [];
        /** @var  bool */
        private static $didInit = false;

        /**
         * Loads Patchwork, use in setUp method of the test case.
         *
         * @return void
         */
        public static function setUp()
        {
            if (!self::$didInit) {
                self::init();
            }
        }

        public static function init(array $options = null)
        {
            if (self::$didInit) {
                return;
            }

            /** @noinspection PhpIncludeInspection */
            require_once Utils::getPatchworkFilePath();

            $_whitelist = is_array($options['include']) ? array_merge(self::$defaultWhitelist, $options['include']) : self::$defaultWhitelist;
            $_blacklist = is_array($options['exclude']) ? array_merge(self::$defaultBlacklist, $options['exclude']) : self::$defaultBlacklist;

            $rootDir = Utils::findParentContainingFrom('vendor', dirname(__FILE__));
            $whitelist = Utils::filterPathListFrom($_whitelist, $rootDir);
            $blacklist = Utils::filterPathListFrom($_blacklist, $rootDir);

            $blacklist = array_diff($blacklist, $whitelist);

            array_map(function ($path) {
                \Patchwork\blacklist($path);
            }, $blacklist);

            self::$didInit = true;
        }

        /**
         * Undoes Patchwork bindings, use in tearDown method of test case.
         *
         * @return void
         */
        public static function tearDown()
        {
            \Patchwork\undoAll();

            // restore the globals
            if (empty(self::$globalsBackup)) {
                return;
            }
            array_walk(self::$globalsBackup, function ($value, $key) {
                $GLOBALS[$key] = $value;
            });
        }

        /**
         * Replaces a function, a static method or an instance method.
         *
         * The function or methods to be replaced must be specified with fully
         * qualified names like
         *
         *     FunctionMocker::replace('my\name\space\aFunction');
         *     FunctionMocker::replace('my\name\space\SomeClass::someMethod');
         *
         * not specifying a return value will make the replaced function or value
         * return `null`.
         *
         * @param      $functionName
         * @param null $returnValue
         *
         * @return mixed|Call\Verifier\InstanceMethodCallVerifier|static
         */
        public static function replace($functionName, $returnValue = null)
        {
            \Arg::_($functionName, 'Function name')->is_string()->_or()->is_array();
            if (is_array($functionName)) {
                $replacements = array();
                array_map(function ($_functionName) use ($returnValue, &$replacements) {
                    $replacements[] = self::_replace($_functionName, $returnValue);
                }, $functionName);

                $indexedReplacements = self::getIndexedReplacements($replacements);

                return $indexedReplacements;
            }

            return self::_replace($functionName, $returnValue);
        }

        /**
         * @param $functionName
         * @param $returnValue
         *
         * @return mixed|null|Call\Verifier\InstanceMethodCallVerifier|static
         * @throws \Exception
         */
        private static function _replace($functionName, $returnValue)
        {
            $request = ReplacementRequest::on($functionName);
            $returnValue = ReturnValue::from($returnValue);
            $methodName = $request->getMethodName();

            if ($request->isClass()) {
                return self::get_instance_replacement_chain_head($functionName);
            }
            if ($request->isInstanceMethod()) {
                return self::get_instance_replacement($request, $returnValue);
            }

            return self::get_function_or_static_method_replacement($functionName, $returnValue, $request, $methodName);
        }

        private static function get_instance_replacement_chain_head($className)
        {
            $step = new Step();
            $step->setClass($className);
            $forger = new InstanceForger();
            $forger->setTestCase(self::getTestCase());
            $step->setInstanceForger($forger);

            return $step;
        }

        /**
         * @return SpoofTestCase
         */
        public static function getTestCase()
        {
            if (!self::$testCase) {
                self::$testCase = new SpoofTestCase();
            }
            $testCase = self::$testCase;

            return $testCase;
        }

        /**
         * @param \PHPUnit_Framework_TestCase $testCase
         */
        public static function setTestCase($testCase)
        {
            self::$testCase = $testCase;
        }

        /**
         * @param ReplacementRequest $request
         * @param $returnValue
         *
         * @return mixed
         */
        public static function get_instance_replacement(ReplacementRequest $request, $returnValue)
        {
            $forger = new InstanceForger();
            $forger->setTestCase(self::getTestCase());

            return $forger->getMock($request, $returnValue);
        }

        /**
         * @param $functionName
         * @param $returnValue
         * @param $request
         * @param $methodName
         *
         * @return Call\Verifier\InstanceMethodCallVerifier|static
         * @throws \Exception
         */
        private static function get_function_or_static_method_replacement($functionName, $returnValue, $request, $methodName)
        {
            $checker = Checker::fromName($functionName);
            $callLogger = CallLoggerFactory::make($functionName);
            $verifier = CallVerifierFactory::make($request, $checker, $returnValue, $callLogger);
            self::replace_with_patchwork($functionName, $returnValue, $request, $methodName, $callLogger);

            return $verifier;
        }

        /**
         * @param $functionName
         * @param $returnValue
         * @param $request
         * @param $methodName
         * @param $callLogger
         */
        private static function replace_with_patchwork($functionName, ReturnValue $returnValue, ReplacementRequest $request, $methodName, LoggerInterface $callLogger)
        {
            $functionOrMethodName = $request->isMethod() ? $methodName : $functionName;

            $replacementFunction = self::getReplacementFunction($functionOrMethodName, $returnValue, $callLogger);

            if (function_exists('\Patchwork\replace')) {

                \Patchwork\replace($functionName, $replacementFunction);
            }
        }

        /**
         * @param $functionName
         * @param $returnValue
         * @param $invocation
         *
         * @return callable
         */
        protected static function getReplacementFunction($functionName, $returnValue, $invocation)
        {
            $replacementFunction = function () use ($functionName, $returnValue, $invocation) {
                $trace = debug_backtrace();
                $args = array_filter($trace, function ($stackLog) use ($functionName) {
                    $check = isset($stackLog['args']) && is_array($stackLog['args']) && $stackLog['function'] === $functionName;

                    return $check ? true : false;
                });
                $args = array_values($args);
                $args = isset($args[0]) ? $args[0]['args'] : array();
                /** @noinspection PhpUndefinedMethodInspection */
                $invocation->called($args);

                /** @noinspection PhpUndefinedMethodInspection */
                return $returnValue->isCallable() ? $returnValue->call($args) : $returnValue->getValue();
            };

            return $replacementFunction;
        }

        /**
         * @param $return
         *
         * @return array
         */
        private static function getIndexedReplacements($return)
        {
            $indexedReplacements = array();
            if ($return[0] instanceof FunctionCallVerifier) {
                array_map(function (FunctionCallVerifier $replacement) use (&$indexedReplacements) {
                    $fullFunctionName = $replacement->__getFunctionName();
                    $functionNameElements = preg_split('/(\\\\|::)/', $fullFunctionName);
                    $functionName = array_pop($functionNameElements);
                    $indexedReplacements[$functionName] = $replacement;
                }, $return);

            }

            return $indexedReplacements;
        }

        /**
         * Calls the original function or static method with the given arguments
         * and returns the return value if any.
         *
         * @param array $args
         *
         * @return mixed
         */
        public static function callOriginal(array $args = null)
        {
            return \Patchwork\callOriginal($args);
        }

        /**
         * Replaces/sets a global object with an instance replacement of the class.
         *
         * The $GLOBALS state will be reset at the next `FunctionMocker::tearDown` call.
         *
         * @param  string $globalHandle The key the value is associated to in the $GLOBALS array.
         * @param  string $functionName A `Class::method` format string
         * @param  mixed $returnValue The return value or callback, see `replace` method.
         *
         * @return mixed               The object that's been set in the $GLOBALS array.
         */
        public static function replaceGlobal($globalHandle, $functionName, $returnValue = null)
        {
            \Arg::_($globalHandle, 'Global var key')->is_string();

            self::backupGlobal($globalHandle);

            $replacement = FunctionMocker::_replace($functionName, $returnValue);
            $GLOBALS[$globalHandle] = $replacement;

            return $replacement;
        }

        protected static function backupGlobal($globalHandle)
        {
            $shouldSave = !isset(self::$globalsBackup[$globalHandle]);
            if (!$shouldSave) {
                return;
            }
            self::$globalsBackup[$globalHandle] = isset($GLOBALS[$globalHandle]) ? $GLOBALS[$globalHandle] : null;
        }

        /**
         * Sets a global value restoring the state after the test ran.
         *
         * @param string $globalHandle The key the value will be associated to in the $GLOBALS array.
         * @param mixed $replacement The value that will be set in the $GLOBALS array.
         *
         * @return mixed               The object that's been set in the $GLOBALS array.
         */
        public static function setGlobal($globalHandle, $replacement = null)
        {
            \Arg::_($globalHandle, 'Global var key')->is_string();

            self::backupGlobal($globalHandle);

            $GLOBALS[$globalHandle] = $replacement;

            return $replacement;
        }

        public static function forge($class)
        {
            return new Step($class);
        }
    }
