<?php

namespace Tribe\Project\Templates\Components\image;

class Image_ControllerTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \IntegrationTester
	 */
	protected $tester;

	public function setUp(): void {
		// Before...
		parent::setUp();

		// Your set up methods here.
	}

	public function tearDown(): void {
		// Your tear down methods here.

		// Then...
		parent::tearDown();
	}

	// Tests
	public function test_builds_image_srcset(): void {
		$backup_sizes                          = $GLOBALS['_wp_additional_image_sizes'];
		$GLOBALS['_wp_additional_image_sizes'] = [];
		add_image_size( 'test_large', 2000, 1600, true );
		add_image_size( 'test_medium', 900, 600, true );
		add_image_size( 'test_small', 400, 300, true );

		// create the test file
		$filename      = codecept_data_dir( '1200x600.png' );
		$attachment_id = self::factory()->attachment->create_upload_object( $filename, 0 );

		$controller = new Image_Controller( [
			Image_Controller::IMG_ID       => $attachment_id,
			Image_Controller::SRC          => true,
			Image_Controller::SRC_SIZE     => 'test_medium',
			Image_Controller::SRCSET_SIZES => [ 'test_large', 'test_medium', 'test_small' ],
		] );

		$srcset = explode( ", \n", $controller->get_srcset_attribute() );

		// clean up
		wp_delete_attachment( $attachment_id, true );
		$GLOBALS['_wp_additional_image_sizes'] = $backup_sizes;

		self::assertCount( 2, $srcset );
		self::assertStringContainsString( '1200x600-900x600.png 900w 600h', $srcset[0] );
		self::assertStringContainsString( '1200x600-400x300.png 400w 300h', $srcset[1] );
	}
}
