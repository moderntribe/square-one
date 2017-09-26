<?php

namespace tad\FunctionMocker;

/**
 * Inits the mocking engine including the Patchwork library.
 *
 * @param array|null $options An array of options to init the Patchwork library.
 *                            ['include'|'whitelist']     array|string A list of absolute paths that should be included in the patching.
 *                            ['exclude'|'blacklist']     array|string A list of absolute paths that should be excluded in the patching.
 *                            ['cache-path']              string The absolute path to the folder where Pathcwork should cache the wrapped files.
 *                            ['redefinable-internals']   array A list of internal PHP functions that are available for replacement.
 *
 * @see \Patchwork\configure()
 *
 */
function init(array $options = null) {
    FunctionMocker::init($options);
}

/**
 * Loads Patchwork, use in setUp method of the test case.
 *
 * @return void
 */
function setUp() {
    FunctionMocker::setUp();
}

/**
 * Undoes Patchwork bindings, use in tearDown method of test case.
 *
 * @return void
 */
function tearDown() {
    FunctionMocker::tearDown();
}

/**
 * Replaces a function, a static method or an instance method.
 *
 * The function or methods to be replaced must be specified with fully
 * qualified names like
 *
 *     tad\FunctionMocker\replace('my\name\space\aFunction');
 *     tad\FunctionMocker\replace('my\name\space\SomeClass::someMethod');
 *
 * not specifying a return value will make the replaced function or value
 * return `null`.
 *
 * @param      $functionName
 * @param null $returnValue
 *
 * @return mixed|Call\Verifier\InstanceMethodCallVerifier|\tad\FunctionMocker\FunctionMocker|\tad\FunctionMocker\Forge\Step
 */
function replace($functionName, $returnValue = null) {
    return FunctionMocker::replace($functionName, $returnValue);
}
