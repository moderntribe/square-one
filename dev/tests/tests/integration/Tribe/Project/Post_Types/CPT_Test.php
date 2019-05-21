<?php

use Tribe\Tests\SquareOneTestCase;
use Tribe\Libs\Object_Meta\Meta_Map;
use Tribe\Libs\Post_Type\Post_Object;
use Tribe\Project\Service_Providers\Object_Meta_Provider;

class CPT_Test extends SquareOneTestCase {

	public function setUp() {
		parent::setUp();

		$this->factory()->acf_field      = new \Tribe\Tests\Factories\ACF_Field;

		try {
			$this->factory()->acf_meta_group = new \Tribe\Tests\Factories\ACF_Meta_Group();
		} catch(Exception $e) {
			$this->markTestSkipped($e->getMessage());
		}
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * @param int      $post_id
	 * @param Meta_Map $meta_map
	 *
	 * @return Post_Object
	 */
	protected function make_post(int $post_id, Meta_Map $meta_map) {
		return new class($post_id, $meta_map) extends Post_Object {
			const NAME = 'cpt_test';

			public function __construct( $post_id = 0, Meta_Map $meta = null ) {
				parent::__construct( $post_id, $meta );
			}
		};
	}

	/** @test */
	public function should_set_and_get_a_meta_field() {
		$container = tribe_project()->container();

		$post_id = $this->factory()->post->create([
			'post_type' => 'cpt_test',
		]);

		$meta_group = $this->factory()->acf_meta_group
			->with_name('cpt_test_meta')
			->with_location([
				'post_types' => [ 'cpt_test' ]
			])
			->with_fields([
				$this->factory()->acf_field->with_name('cpt_field')->create(),
			])
			->create();

		$container[Object_Meta_Provider::REPO]->add_group($meta_group);
		$meta_map = $container[Object_Meta_Provider::REPO]->get('cpt_test');

		$post_object = $this->make_post($post_id, $meta_map);

		/**
		 * In essence, the assertion happens here: We update the field directly in ACF,
		 * then we request our Meta system to retrieve the value for that key.
		 */
		update_field('cpt_field', 'foo_value', $post_id);
		$get_meta_value = $post_object->get_meta('cpt_field');

		$this->assertEquals('foo_value', $get_meta_value);
	}
}