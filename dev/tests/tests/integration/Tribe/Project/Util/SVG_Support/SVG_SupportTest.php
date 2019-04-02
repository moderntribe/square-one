<?php

namespace Tribe\Project\Service_Providers\Post_Types;

use RuntimeException;
use Tribe\Project\Core;

class SVG_SupportTest extends \Codeception\TestCase\WPTestCase {

	/** @var string Path for the SVG file that we use to test */
	protected $svg_path;

	public function setUp() {
		parent::setUp();

		// your set up methods here
		$svg_support = Core::instance()->container()['util.svg_support'];
		$svg_support->add_svg_upload();

		$this->svg_path = codecept_data_dir( 'xss.svg' );
	}

	public function tearDown() {
		// your tear down methods here
		/**
		 * @todo remove ob_start() from SVG_Support::add_svg_upload() so we can remove this
		 */
		ob_end_clean();

		// then
		parent::tearDown();
	}

	/**
	 * @param string $path
	 *
	 * @return array
	 */
	protected function handle_upload( string $path ): array {
		$filename        = basename( $path );
		$random_filename = rand( 0, 1000 ) . $filename;
		$tmp_path        = str_replace( $filename, $random_filename, $path );

		/** Create a temporary copy of the original file, since it will be moved during the upload process */
		copy( $path, $tmp_path );

		if ( file_exists( $tmp_path ) ) {
			$_files_mimic = [
				'name'     => $random_filename,
				'type'     => 'image/svg+xml',
				'tmp_name' => $tmp_path,
				'error'    => 0,
				'size'     => filesize( $tmp_path ),
			];

			return wp_handle_upload( $_files_mimic, [
				'action'    => 'test_svg_upload',
				'test_form' => false,
			] );
		} else {
			throw new RuntimeException( 'Could not copy test file from ' . $path . ' to ' . $tmp_path );
		}
	}

	/** @test */
	public function it_should_recognize_svg_mime_type() {
		$filetype = wp_check_filetype( 'test.svg' );
		$this->assertEquals( 'image/svg+xml', $filetype['type'] );
	}

	/** @test */
	public function it_should_strip_disallowed_svg_tags() {
		add_filter( 'tribe_svg_allowed_tags', function ( $allowed_tags ) {
			return array_diff( $allowed_tags, [ 'script' ] );
		} );

		$upload_response = $this->handle_upload( $this->svg_path );

		$uploaded_svg = file_get_contents( $upload_response['file'] );

		$this->assertArrayHasKey( 'file', $upload_response );
		$this->assertArrayNotHasKey( 'error', $upload_response );
		$this->assertNotContains( '<script ', $uploaded_svg );

		unlink( $upload_response['file'] );
	}

	/**
	 * @test
	 * @todo replace assertContains with regex version
	 */
	public function it_should_keep_allowed_svg_tags() {
		add_filter( 'tribe_svg_allowed_tags', function ( $allowed_tags ) {
			$allowed_tags[] = 'script';

			return $allowed_tags;
		} );

		$upload_response = $this->handle_upload( $this->svg_path );

		$uploaded_svg = file_get_contents( $upload_response['file'] );

		$this->assertArrayHasKey( 'file', $upload_response );
		$this->assertArrayNotHasKey( 'error', $upload_response );
		$this->assertContains( '<script ', $uploaded_svg );

		unlink( $upload_response['file'] );
	}

	/** @test */
	public function it_should_strip_disallowed_svg_attributes() {
		add_filter( 'tribe_svg_allowed_attributes', function ( $allowed_attributes ) {
			return array_diff( $allowed_attributes, [ 'style' ] );
		} );

		$upload_response = $this->handle_upload( $this->svg_path );

		$uploaded_svg = file_get_contents( $upload_response['file'] );

		$this->assertArrayHasKey( 'file', $upload_response );
		$this->assertArrayNotHasKey( 'error', $upload_response );
		$this->assertNotContains( 'style=', $uploaded_svg );

		unlink( $upload_response['file'] );
	}

	/** @test */
	public function it_should_keep_allowed_svg_attributes() {
		add_filter( 'tribe_svg_allowed_attributes', function ( $allowed_attributes ) {
			$allowed_attributes[] = 'style';

			return $allowed_attributes;
		} );

		$upload_response = $this->handle_upload( $this->svg_path );

		$uploaded_svg = file_get_contents( $upload_response['file'] );

		$this->assertArrayHasKey( 'file', $upload_response );
		$this->assertArrayNotHasKey( 'error', $upload_response );
		$this->assertContains( 'style=', $uploaded_svg );

		unlink( $upload_response['file'] );
	}
}