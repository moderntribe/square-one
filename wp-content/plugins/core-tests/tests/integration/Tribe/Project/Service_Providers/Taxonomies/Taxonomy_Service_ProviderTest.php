<?php

namespace Tribe\Project\Service_Providers\Taxonomies;

class Taxonomy_Service_ProviderTest extends \Codeception\TestCase\WPTestCase {

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


	public function test_requires_taxonomy_class() {
		$this->expectException(\LogicException::class);
		new class extends Taxonomy_Service_Provider {};
	}

	public function test_requires_name_constant() {
		$taxonomy_class = new class {};
		$this->expectException(\LogicException::class);
		new class ( $taxonomy_class ) extends Taxonomy_Service_Provider {
			public function __construct( $taxonomy_class ) {
				$this->taxonomy_class = get_class($taxonomy_class);
				parent::__construct();
			}
		};
	}

	public function test_works_when_name_constant_is_set() {
		$taxonomy_class = new class {
			const NAME = 'something';
		};
		new class ( $taxonomy_class ) extends Taxonomy_Service_Provider {
			public function __construct( $taxonomy_class ) {
				$this->taxonomy_class = get_class($taxonomy_class);
				parent::__construct();
			}
		};
		// no exceptions, we're good
	}
}