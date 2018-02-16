<?php
namespace Tribe\Project\CLI;

class Pimple_DumpTest extends \Codeception\TestCase\WPTestCase
{

    public function setUp()
    {
        // before
        parent::setUp();

        // your set up methods here
    }

    public function tearDown()
    {
        // your tear down methods here

        // then
        parent::tearDown();
    }

    // tests
    public function testMe()
    {
    }

	public function test_requires_container() {
		$this->expectException(\LogicException::class);
		//new class extends Pimple_Dump {};
	}

}