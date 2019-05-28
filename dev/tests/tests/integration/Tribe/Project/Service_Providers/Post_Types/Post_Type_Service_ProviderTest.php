<?php

namespace Tribe\Project\Service_Providers\Post_Types;

use Tribe\Libs\Post_Type\Post_Object;
use Tribe\Tests\Test_Case;

class Post_Type_Service_ProviderTest extends Test_Case {
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
	public function should_not_throw_if_correct_input_given() {
		$post_type_class = new class extends Post_Object {
			const NAME = 'something';
		};
		new class ( $post_type_class ) extends Post_Type_Service_Provider {
			public function __construct( $post_type_class ) {
				$this->post_type_class = get_class($post_type_class);
				parent::__construct();
			}
		};
		// no exceptions, we're good
	}

	/** @test */
	public function should_require_post_type_class() {
		$this->expectException(\LogicException::class);
		new class extends Post_Type_Service_Provider {};
	}

	/** @test */
	public function should_require_name_constant() {
		$this->expectException(\LogicException::class);
		$post_type_class = new class extends Post_Object {};
		new class ( $post_type_class ) extends Post_Type_Service_Provider {
			public function __construct( $post_type_class ) {
				$this->post_type_class = get_class($post_type_class);
				parent::__construct();
			}
		};
	}

	/** @test */
	public function should_throw_if_not_extending_post_object() {
		$this->expectException(\LogicException::class);
		$post_type_class = new class {
			const NAME = 'something';
		};
		new class ( $post_type_class ) extends Post_Type_Service_Provider {
			public function __construct( $post_type_class ) {
				$this->post_type_class = get_class($post_type_class);
				parent::__construct();
			}
		};
	}

	/** @test */
	public function should_throw_if_providing_a_config_class_that_does_not_extend_post_type_config() {
		$this->expectException(\LogicException::class);
		$post_type_class = new class extends Post_Object {
			const NAME = 'something';
		};
		$provider = new class ( $post_type_class ) extends Post_Type_Service_Provider {
			public function __construct( $post_type_class ) {
				$this->post_type_class = get_class($post_type_class);
				$this->config_class = 'foo';
				parent::__construct();
			}
		};

		$provider->register( tribe_project()->container() );
	}
}