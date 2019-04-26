<?php

namespace Tribe\Project\Queues;

use Codeception\TestCase\WPTestCase;
use Tribe\Project\Queues\Backends\Mock_Backend;

/**
 * Class Queue_Collection_Test
 * @package Tribe\Project\Queues
 */
class Queue_Collection_Test extends WPTestCase {

	/** @var Queue_Collection */
	protected $collection;

	/** @var DefaultQueue */
	protected $queue;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		$collection = new Queue_Collection();
		$this->collection = $collection;

		$queue = new DefaultQueue(new Mock_Backend());
		$this->queue = $queue;
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/** @test */
	public function should_add_a_queue() {
		$this->collection->add($this->queue);

		$result = $this->collection->get(DefaultQueue::NAME);

		$this->assertInstanceOf( DefaultQueue::class, $result );
	}

	/** @test */
	public function should_remove_a_queue() {
		$this->expectException(\DomainException::class);

		$this->collection->add($this->queue);
		$this->collection->remove(DefaultQueue::NAME);
		$this->collection->get(DefaultQueue::NAME);
	}

	/** @test */
	public function should_throw_if_queue_does_not_exist() {
		$this->expectException(\DomainException::class);

		$this->collection->get('I am a queue that definetly does not exist!');
	}
}
