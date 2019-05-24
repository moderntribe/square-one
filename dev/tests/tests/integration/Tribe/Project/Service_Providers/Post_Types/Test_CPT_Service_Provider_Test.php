<?php

namespace Tribe\Tests\Service_Providers\Post_Types;

use Pimple\Container;
use Prophecy\Argument;
use Tribe\Tests\Post_Types\Test_CPT\Test_CPT;
use Tribe\Tests\SquareOneTestCase;

class Test_CPT_Service_Provider_Test extends SquareOneTestCase {

	protected $original_providers;

	public function setUp() {
		// before
		parent::setUp();

		// your set up methods here
		unregister_post_type( Test_CPT::NAME );
	}

	public function tearDown() {
		// your tear down methods here

		// then
		parent::tearDown();
	}

	/** @test */
	public function should_call_register() {
		$mock                         = $this->prophesize( Test_CPT_Service_Provider::class );
		$to_replace_service_providers = function () use ( $mock ) {
			return [ 'post_types.test_cpt' => $mock->reveal() ];
		};
		add_filter( 'tribe/project/providers', $to_replace_service_providers );

		tribe_project()->init();

		$mock->register( Argument::type( Container::class ) )->shouldHaveBeenCalledOnce();
	}
}