<?php

use Tribe\Tests\Test_Case;
use Tribe\Project\Service_Providers\Object_Meta_Provider;
use Tribe\Libs\ACF\ACF_Meta_Group;

/**
 * Class ACF_Meta_Groups_In_Provider_Test
 *
 * We run assertions on every ACF_Meta_Group class
 * registered by the Object_Meta_Provider.
 *
 * @see \Tribe\Project\Service_Providers\Object_Meta_Provider_Test
 */
class ACF_Meta_Groups_In_Provider_Test extends Test_Case {

	protected $acf_meta_groups;

	public function setUp() {
		parent::setUp();

		if ( is_null( $this->acf_meta_groups ) ) {
			$om_provider = $this->get_provider();
			$meta_groups = $om_provider->get_keys();
			foreach ( $meta_groups as $meta_group ) {
				$meta_group_instance = tribe_project()->container()[ $meta_group ];
				if ( $meta_group_instance instanceof ACF_Meta_Group ) {
					$this->acf_meta_groups[] = $meta_group_instance;
				} else {
					codecept_debug( sprintf(
						'Skipping %s as this class does not extend from ACF_Meta_Group',
						get_class( $meta_group_instance )
					) );
				}
			}
		}
	}

	protected function get_provider() {
		return new Object_Meta_Provider();
	}

	/** @test */
	public function acf_should_be_active() {
		$acf = acf();
		$this->assertInstanceOf( ACF::class, $acf );
	}

	/** @test */
	public function acf_should_return_path() {
		$this->assertTrue( function_exists( 'acf_get_path' ) );
	}

	/** @test */
	public function should_have_a_name_const() {
		/** @var ACF_Meta_Group $meta_group */
		foreach ( $this->acf_meta_groups as $meta_group ) {
			$this->assertNotEmpty( $meta_group::NAME, sprintf(
				'%s must declare "const NAME"',
				get_class( $meta_group )
			) );
		}
	}

	/** @test */
	public function should_have_a_location_assigned() {
		/** @var ACF_Meta_Group $meta_group */
		foreach ( $this->acf_meta_groups as $meta_group ) {
			$locations = $meta_group->get_object_types();

			// An empty post_types array is not a valid location and interferes with assertNotEmpty() assertion
			if ( empty( $locations['post_types'] ) ) {
				unset( $locations['post_types'] );
			}

			$this->assertNotEmpty( $locations, sprintf(
				"\nMeta_Group %s registered in the Object_Meta_Provider does not have a location assigned to it.\n",
				get_class( $meta_group )
			) );
		}
	}

	/** @test */
	public function should_have_been_registered_in_acf() {
		/** @var ACF_Meta_Group $meta_group */
		foreach ( $this->acf_meta_groups as $meta_group ) {
			$acf_group = acf_get_field_group( 'group_' . $meta_group::NAME );

			$this->assertNotFalse( $acf_group );
		}
	}

	/** @test */
	public function should_have_fields() {
		/** @var ACF_Meta_Group $meta_group */
		foreach ( $this->acf_meta_groups as $meta_group ) {
			$acf_fields = acf_get_fields( 'group_' . $meta_group::NAME );

			$this->assertNotEmpty( $acf_fields );
		}
	}

	/**
	 * The purpose of this test is to assert that all fields in all ACF_Meta_Groups of this application we pass to ACF
	 * valid keys for the fields registration. Example:
	 *
	 * ACF/Field::add_field([ 'type' => 'text', 'foo' => 'bar']);
	 *
	 * Fails, because "foo" key is not expected for field with "text" type.
	 *
	 * This test can be adjusted to each project with two filters:
	 *
	 * tribe/tests/acf_meta_group/test_field_type to allow for certain field types to ignore valid keys check
	 * tribe/tests/acf_meta_group/valid_keys to allow granular control of valid keys for each field type
	 *
	 * @test
	 */
	public function should_have_valid_keys_in_every_field() {
		$minimum_version = "5.7.10";
		$acf_version     = acf()->version;

		if ( version_compare( $acf_version, $minimum_version, "<" ) ) {
			$this->markTestSkipped( sprintf(
				'ACF must be version %s or higher in order to run this test. You have %s.',
				$minimum_version,
				$acf_version
			) );
		}


		// We add some keys that we consider to be valid for given field types.
		add_filter( 'tribe/tests/acf_meta_group/valid_keys', function ( $valid_keys, $field_type ) {
			switch ( $field_type ) {
				case 'text':
				case 'textarea':
				case 'email':
					// Even though "readonly" and "disabled" are valid field keys for certain field types,
					// acf_validate_field() doesn't return them, so we have to do it manually:
					$valid_keys['readonly'] = '';
					$valid_keys['disabled'] = '';
					break;
				case 'swatch':
					// Custom ACF Field, added through plugin
					$valid_keys['layout']        = '';
					$valid_keys['allow_null']    = '';
					$valid_keys['default_value'] = '';
					$valid_keys['choices']       = '';
					break;
			}

			return $valid_keys;
		}, 10, 2 );

		/** @var ACF_Meta_Group $meta_group */
		foreach ( $this->acf_meta_groups as $meta_group ) {
			$acf_fields = acf_get_fields( 'group_' . $meta_group::NAME );

			foreach ( $acf_fields as $field ) {
				$should_test = apply_filters( 'tribe/tests/acf_meta_group/test_field_type', true, $field['type'] );

				if ( $should_test === false ) {
					// Only shown with --debug flag
					codecept_debug( sprintf(
						'Skipped testing valid keys for field type: %s',
						$field['type']
					) );
					continue 1;
				}

				// We ask ACF for the default keys for given type
				$valid_keys = acf_validate_field( [
					'type' => $field['type'],
				] );

				// Allow developers to specify which keys are valid per field type
				$valid_keys = apply_filters( 'tribe/tests/acf_meta_group/valid_keys', $valid_keys, $field['type'] );

				// Then we assert there's a default for each key we register
				foreach ( $field as $key => $value ) {
					$this->assertArrayHasKey( $key, $valid_keys,
						sprintf(
							"\n Unrecognized key in ACF\Field object. \n\n Class: %s \n Field Name: %s \n Field Type: %s \n Unrecognized Key: %s \n\n %s",
							get_class( $meta_group ),
							$field['name'],
							$field['type'],
							$key,
							"If this is a valid key (eg: custom field type), register new accepted keys with filter \"tribe/tests/acf_meta_group/valid_keys\"\n\n"
						)
					);
				}
			}
		}
	}

	/** @test */
	public function repeater_fields_should_have_sub_fields() {
		/** @var ACF_Meta_Group $meta_group */
		foreach ( $this->acf_meta_groups as $meta_group ) {
			$acf_fields = acf_get_fields( 'group_' . $meta_group::NAME );

			foreach ( $acf_fields as $field ) {
				if ( $field['type'] === 'repeater' ) {
					$this->assertNotEmpty( $field['sub_fields'] );
				}
			}
		}
	}

	/** @test */
	public function flexible_content_fields_should_have_layout_and_sub_fields() {
		/** @var ACF_Meta_Group $meta_group */
		foreach ( $this->acf_meta_groups as $meta_group ) {
			$acf_fields = acf_get_fields( 'group_' . $meta_group::NAME );

			foreach ( $acf_fields as $field ) {
				if ( $field['type'] === 'flexible_content' ) {
					$this->assertNotEmpty( $field['layouts'] );
					foreach ( $field['layouts'] as $layout ) {
						$this->assertNotEmpty( $layout['sub_fields'] );
					}
				}
			}
		}
	}
}