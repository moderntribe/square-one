<?php

namespace Tribe\Project\Service_Providers\Post_Types;

class Post_Type_Service_ProviderTest extends \Codeception\TestCase\WPTestCase {

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

	public function test_requires_post_type_class() {
		$this->expectException(\LogicException::class);
		new class extends Post_Type_Service_Provider {};
	}

	public function test_requires_name_constant() {
		$post_type_class = new class {};
		$this->expectException(\LogicException::class);
		new class ( $post_type_class ) extends Post_Type_Service_Provider {
			public function __construct( $post_type_class ) {
				$this->post_type_class = get_class($post_type_class);
				parent::__construct();
			}
		};
	}

	public function test_works_when_name_constant_is_set() {
		$post_type_class = new class {
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

}