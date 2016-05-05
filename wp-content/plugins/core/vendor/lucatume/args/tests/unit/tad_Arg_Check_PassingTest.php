<?php
class tad_Arg_Check_PassingTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var tad_Arg_Check
     */
    private $tad_arg_check;

    /**
     * @covers tad_Arg_Check::__construct
     * @covers tad_Arg_Check::setState
     */
    protected function setUp()
    {
        $this->tad_arg_check = new tad_Arg_Check(new tad_Arg_Check_PassingState);
    }

    /**
     * @covers tad_Arg_Check::is_passing
     */
    public function testIs_passing()
    {
        $this->assertTrue($this->tad_arg_check->is_passing());
    }

    /**
     * @covers tad_Arg_Check::is_failing
     */
    public function testIsNot_failing()
    {
        $this->assertFalse($this->tad_arg_check->is_failing());
    }

    /**
     * @covers tad_Arg_Check::is_or_failing
     */
    public function testIsNot_or_failing()
    {
        $this->assertFalse($this->tad_arg_check->is_or_failing());
    }

    /**
     * @covers tad_Arg_Check::is_failed
     */
    public function testIsNot_failed()
    {
        $this->assertFalse($this->tad_arg_check->is_failed());
    }

    /**
     * @covers tad_Arg_Check::pass
     * @covers tad_Arg_Check_PassingState::pass
     * @uses   tad_Arg_Check::is_passing
     */
    public function testCanPass()
    {
        $this->tad_arg_check->pass();
        $this->assertTrue($this->tad_arg_check->is_passing());
    }

    /**
     * @covers tad_Arg_Check::fail
     * @covers tad_Arg_Check_PassingState::fail
     * @uses   tad_Arg_Check::is_failing
     */
    public function testCanFail()
    {
        $this->tad_arg_check->fail();
        $this->assertTrue($this->tad_arg_check->is_failing());
    }

    /**
     * @covers tad_Arg_Check::or_condition
     * @covers tad_Arg_Check_PassingState::or_condition
     * @uses   tad_Arg_Check::is_passing
     */
    public function testCanApplyOrCondition()
    {
        $this->tad_arg_check->or_condition();
        $this->assertFalse($this->tad_arg_check->is_passing());
    }
}
