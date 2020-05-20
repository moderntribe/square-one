<?php
declare( strict_types=1 );

namespace Tribe\Project\Theme\Media;

use tad\FunctionMocker\FunctionMocker;

class Image_WrapTest extends \Codeception\Test\Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _setUpBeforeClass(): void {
		FunctionMocker::setUp();
		FunctionMocker::replace( 'is_singular', static function () {
			return true;
		} );
		FunctionMocker::replace( 'in_the_loop', static function () {
			return true;
		} );
		FunctionMocker::replace( 'is_main_query', static function () {
			return true;
		} );
	}

	protected function _tearDownAfterClass(): void {
		FunctionMocker::tearDown();
	}

	public function test_wraps_image_without_caption() {
		$wrap = new Image_Wrap();

		$original_markup = '<p><img class="alignnone size-medium wp-image-130" src="https://example.com/image.jpg" alt="" width="300" height="300" /></p>';
		$expected_markup = '<figure class="wp-image wp-image--no-caption alignnone"><img class=" size-medium wp-image-130" src="https://example.com/image.jpg" alt="" width="300" height="300" /></figure>';

		$this->assertEquals( $expected_markup, $wrap->customize_wp_image_non_captioned_output( $original_markup ) );
	}

	public function test_wraps_image_with_caption() {
		$wrap = new Image_Wrap();

		$original_markup = '<figure id="attachment_129" aria-describedby="caption-attachment-129" style="width: 300px" class="wp-caption alignnone"><img class="wp-image-129 size-medium" src="https://example.com/image.jpg" alt="" width="300" height="300" /><figcaption id="caption-attachment-129" class="wp-caption-text">Text for Caption</figcaption></figure>';
		$expected_markup = '<figure id="attachment_129" aria-describedby="caption-attachment-129" style="width: 300px" class="wp-image wp-image--caption alignnone"><img class="wp-image-129 size-medium" src="https://example.com/image.jpg" alt="" width="300" height="300" /><figcaption id="caption-attachment-129" class="wp-caption-text" style="width: 300px">Text for Caption</figcaption></figure>';

		$this->assertEquals( $expected_markup, $wrap->customize_wp_image_captioned_output( $original_markup ) );
	}

	public function test_does_not_double_wrap_images() {
		$wrap = new Image_Wrap();

		$original_markup = '
			<p><img class="alignnone size-medium wp-image-130" src="https://example.com/image.jpg" alt="" width="300" height="300" /></p>
			<figure id="attachment_129" aria-describedby="caption-attachment-129" style="width: 300px" class="wp-caption alignnone"><img class="wp-image-129 size-medium" src="https://example.com/image.jpg" alt="" width="300" height="300" /><figcaption id="caption-attachment-129" class="wp-caption-text">Text for Caption</figcaption></figure>
			';
		$expected_markup = '
			<figure class="wp-image wp-image--no-caption alignnone"><img class=" size-medium wp-image-130" src="https://example.com/image.jpg" alt="" width="300" height="300" /></figure>
			<figure id="attachment_129" aria-describedby="caption-attachment-129" style="width: 300px" class="wp-image wp-image--caption alignnone"><img class="wp-image-129 size-medium" src="https://example.com/image.jpg" alt="" width="300" height="300" /><figcaption id="caption-attachment-129" class="wp-caption-text" style="width: 300px">Text for Caption</figcaption></figure>
			';

		$filtered_once  = $wrap->customize_wp_image_non_captioned_output( $original_markup );
		$filtered_twice = $wrap->customize_wp_image_captioned_output( $filtered_once );
		$this->assertEquals( $expected_markup, $filtered_twice );
	}
}
