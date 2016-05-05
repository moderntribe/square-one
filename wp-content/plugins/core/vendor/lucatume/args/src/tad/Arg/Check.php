<?php
class tad_Arg_Check
{
    /**
     * @var tad_Arg_Check_State
     */
    private $state;

    public function __construct(tad_Arg_Check_State $state)
    {
        $this->setState($state);
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function pass()
    {
        $this->setState($this->state->pass());
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function fail()
    {
        $this->setState($this->state->fail());
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function or_condition()
    {
        $this->setState($this->state->or_condition());
    }

    /**
     * @return bool
     */
    public function is_passing()
    {
        return $this->state instanceof tad_Arg_Check_PassingState;
    }

    /**
     * @return bool
     */
    public function is_failing()
    {
        return $this->state instanceof tad_Arg_Check_FailingState;
    }

    /**
     * @return bool
     */
    public function is_or_failing()
    {
        return $this->state instanceof tad_Arg_Check_OrFailingState;
    }

    /**
     * @return bool
     */
    public function is_failed()
    {
        return $this->state instanceof tad_Arg_Check_FailedState;
    }

    private function setState(tad_Arg_Check_State $state)
    {
        $this->state = $state;
    }
}
