<?php
class tad_Arg_Check_FailingState extends tad_Arg_Check_AbstractState
{
    /**
     * @return tad_Arg_Check_FailedState
     */
    public function fail()
    {
        return new tad_Arg_Check_FailedState;
    }

    /**
     * @return tad_Arg_Check_FailedState
     */
    public function pass()
    {
        return new tad_Arg_Check_FailedState;
    }

    /**
     * @return tad_Arg_Check_OrFailingState
     */
    public function or_condition()
    {
        return new tad_Arg_Check_OrFailingState;
    }
}
