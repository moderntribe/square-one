<?php
/**
 * Created by IntelliJ IDEA.
 * User: Luca
 * Date: 31/03/15
 * Time: 23:20
 */

namespace tad\FunctionMocker\Forge;


use tad\FunctionMocker\ReplacementRequest;

class InstanceMethodRequest extends ReplacementRequest
{

    public static function instance($class)
    {
        \Arg::_($class, 'Class name')->is_string()->assert(class_exists($class) || interface_exists($class) || trait_exists($class), 'Class must be a defined one');
        $instance = new self;
        $instance->requestClassName = $class;
        $instance->isStaticMethod = false;
        $instance->isInstanceMethod = true;
        $instance->isFunction = false;
        $instance->isMethod = true;
        $instance->methodName = '';

        return $instance;
    }
}