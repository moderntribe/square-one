<?php
interface tad_Arg_Check_State
{
    public function pass();
    public function fail();
    public function or_condition();
}
