<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Models;

class ImageTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \IntegrationTester
	 */
	protected $tester;

	// Tests
	public function test_factory_retrieves_image_data() {
		// register sizes for testing
		add_image_size( 'image-model-small-size', 360, 300, true );
		add_image_size( 'image-model-large-size', 900, 900, true );

		// create the test file
		$filename      = codecept_data_dir( '640x480.png' );
		$attachment_id = self::factory()->attachment->create_upload_object( $filename, 0 );
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', 'alt text' );

		$image = Image::factory( $attachment_id );

		$this->assertEquals( get_the_title( $attachment_id ), $image->title() );
		$this->assertEquals( 'alt text', $image->alt() );

		$full = $image->get_size( 'full' );
		$this->assertEquals( 'full', $full->size );
		$this->assertFalse( $full->is_intermediate );
		$this->assertFalse( $full->is_match );
		$this->assertEquals( wp_get_attachment_image_src( $attachment_id, 'full' )[0], $full->src );

		$thumbnail = $image->get_size( 'thumbnail' );
		$this->assertEquals( 'thumbnail', $thumbnail->size );
		$this->assertTrue( $thumbnail->is_intermediate );
		$this->assertFalse( $thumbnail->is_match );
		$this->assertEquals( wp_get_attachment_image_src( $attachment_id, 'thumbnail' )[0], $thumbnail->src );

		$small = $image->get_size( 'image-model-small-size' );
		$this->assertEquals( 'image-model-small-size', $small->size );
		$this->assertTrue( $small->is_intermediate );
		$this->assertTrue( $small->is_match );
		$this->assertEquals( wp_get_attachment_image_src( $attachment_id, 'image-model-small-size' )[0], $small->src );
		$this->assertEquals( 360, $small->width );
		$this->assertequals( 300, $small->height );

		$large = $image->get_size( 'image-model-large-size' );
		$this->assertEquals( 'image-model-large-size', $large->size );
		$this->assertFalse( $large->is_intermediate );
		$this->assertFalse( $large->is_match );
		$this->assertEquals( wp_get_attachment_image_src( $attachment_id, 'image-model-large-size' )[0], $large->src );
		$this->assertEquals( 640, $large->width );
		$this->assertequals( 480, $large->height );

		// clean up
		wp_delete_attachment( $attachment_id, true );
		remove_image_size( 'image-model-small-size' );
		remove_image_size( 'image-model-large-size' );
	}
}
