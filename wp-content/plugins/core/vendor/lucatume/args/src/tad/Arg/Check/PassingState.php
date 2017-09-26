<?php
class tad_Arg_Check_PassingState extends tad_Arg_Check_AbstractState
{
    /**
     * @return tad_Arg_Check_PassingState
     */
    public function pass()
    {
        return new tad_Arg_Check_PassingState;
    }

    /**
     * @return tad_Arg_Check_PassingState
     */
    public function or_condition()
    {
        return new tad_Arg_Check_OrPassingState;
    }

    /**
     * @return tad_Arg_Check_FailingState
     */
    public function fail()
    {
        return new tad_Arg_Check_FailingState;
    }
}
