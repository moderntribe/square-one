<?php
abstract class tad_Arg_Check_AbstractState implements tad_Arg_Check_State
{
    /**
     * @throws IllegalStateTransitionException
     */
    public function pass()
    {
        throw new IllegalStateTransitionException;
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function fail()
    {
        throw new IllegalStateTransitionException;
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function or_condition()
    {
        throw new IllegalStateTransitionException;
    }
}
