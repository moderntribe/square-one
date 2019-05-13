<?php

namespace Tribe\Project\Service_Providers;

use Tribe\Project\Queues\Contracts\Queue;
use Tribe\Tests\SquareOneTestCase;

class Queues_Provider_Test extends SquareOneTestCase {

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/** @test */
	public function should_register_a_queue_if_it_is_set() {
		$queue_const = "DEFAULT_QUEUE";

		// Check if $queue_const is registered in the Queues Provider
		$class_reflex = new \ReflectionClass(new Queues_Provider());
		$class_constants = $class_reflex->getConstants();
		if (array_key_exists($queue_const, $class_constants)) {
			$constant_value = $class_constants[$queue_const];
		} else {
			$constant_value = null;
		}

		if ( empty( $constant_value ) ) {
			$this->markTestSkipped( "Test skipped because it relies on a Queue that Queues_Provider does not register. To make this test pass, provide an existing Queue to " . __METHOD__ );
		}

		$this->assertInstanceOf(Queue::class, tribe_project()->container()[$constant_value]);
	}

}