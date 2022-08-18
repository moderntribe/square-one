<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop;

use Tribe\Tests\Test_Case;

final class PostLoopControllerFactoryTest extends Test_Case {

	public function test_it_makes_a_post_loop_controller(): void {
		$factory    = $this->container->make( Post_Loop_Controller_Factory::class );
		$controller = $factory->make( [] );
		$this->assertInstanceOf( Post_Loop_Controller::class, $controller );
	}

}
