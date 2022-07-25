<?php declare(strict_types=1);

namespace Tribe\Tests;

use Codeception\TestCase\WPTestCase;

/**
 * Class Test_Case
 * Test case with specific actions for Square One projects.
 *
 * @mixin \Codeception\Test\Unit
 * @mixin \PHPUnit\Framework\TestCase
 *
 * @package Tribe\Tests
 */
class Test_Case extends WPTestCase {

	/**
	 * @var \DI\FactoryInterface|\Invoker\InvokerInterface|\Psr\Container\ContainerInterface
	 */
	protected $container;

	public function _setUp() {
		parent::_setUp();

		$this->container = tribe_project()->container();
	}

}
