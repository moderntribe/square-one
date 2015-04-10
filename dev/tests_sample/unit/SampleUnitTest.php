<?php

/**
 * Created by IntelliJ IDEA.
 * User: Luca
 * Date: 31/03/15
 * Time: 16:20
 */
class Test extends PHPUnit_Framework_TestCase {

	/**
	 * @test
	 */
	public function passing_test() {
		$this->assertTrue( true );
	}

	/**
	 * @test
	 */
	public function failing_test() {
		$this->assertFalse( true );
	}
}
