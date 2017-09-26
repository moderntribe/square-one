<?php

namespace tad\FunctionMocker;

/**
 * Creates a null returning function.
 *
 * @param string $functionName
 * @param string $functionNamespace
 *
 * @throws \Exception If the function could not be created.
 */
function create_function($functionName, $functionNamespace = null) {
    $namespace = $functionNamespace ? " {$functionNamespace};" : '';
    $code = trim(sprintf('namespace %s {function %s(){return null;}}', $namespace, $functionName));
    $ok = eval($code);
    if ($ok === false) {
        throw new \Exception("Could not eval code $code for function $functionName");
    }
}
