<?php

namespace tad\FunctionMocker;

class CallTrace
{

    protected $args;

    public static function fromArguments(array $args = null)
    {
        $instance = new self;
        $instance->args = $args ? $args : array();

        return $instance;
    }

    public function getArguments()
    {
        return $this->args;
    }
}
