<?php

use Tribe\Project\Util\SVG_Support\SVG_Support;

class SVG_SupportTest extends \Codeception\TestCase\WPTestCase {

	/** @var string Path for the SVG file that we use to test */
	protected $svg_path;

	public function setUp() {
		parent::setUp();

		$this->svg_path = codecept_data_dir( 'xss.svg' );

		add_filter( 'test_svg_upload_prefilter', [ $this->make_instance(), 'check_for_svg' ] );
	}

	public function tearDown() {
		// your tear down methods here
		/**
		 * @todo remove ob_start() from SVG_Support::add_svg_upload() so we can remove this
		 */
		if ( ob_get_level() > 1 ) {
			ob_end_clean();
		}

		// then
		parent::tearDown();
	}

	/**
	 * @test
	 */
	public function should_be_instantiable() {
		$instance = $this->make_instance();

		$this->assertInstanceOf( SVG_Support::class, $instance );
	}

	protected function make_instance() {
		return new SVG_Support();
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
			$_files_mime = [
				'name'     => $random_filename,
				'type'     => 'image/svg+xml',
				'tmp_name' => $tmp_path,
				'error'    => 0,
				'size'     => filesize( $tmp_path ),
			];

			return wp_handle_upload( $_files_mime, [
				'action'    => 'test_svg_upload',
				'test_form' => false,
			] );
		} else {
			throw new RuntimeException( 'Could not copy test file from ' . $path . ' to ' . $tmp_path );
		}
	}

	/**
	 * @see \Tribe\Project\Util\SVG_Support\SVG_Support::filter_mimes
	 * @test
	 */
	public function should_add_svg_mime_type() {
		$before = wp_check_filetype( 'foo.svg' );

		add_filter( 'upload_mimes', [ $this->make_instance(), 'filter_mimes' ] );
		$after = wp_check_filetype( 'foo.svg' );

		$this->assertFalse( $before['type'] );
		$this->assertEquals( 'image/svg+xml', $after['type'] );
	}

	/** @test */
	public function should_strip_disallowed_svg_tags() {
		$this->make_instance()->add_svg_upload();

		add_filter( 'tribe_svg_allowed_tags', function ( $allowed_tags ) {
			return array_diff( $allowed_tags, [ 'script' ] );
		} );

		$upload_response = $this->handle_upload( $this->svg_path );
		$uploaded_svg    = file_get_contents( $upload_response['file'] );
		unlink( $upload_response['file'] );

		$this->assertArrayHasKey( 'file', $upload_response );
		$this->assertArrayNotHasKey( 'error', $upload_response );
		$this->assertNotContains( '<script ', $uploaded_svg );
	}

	/** @test */
	public function should_keep_allowed_svg_tags() {
		$this->make_instance()->add_svg_upload();

		add_filter( 'tribe_svg_allowed_tags', function ( $allowed_tags ) {
			$allowed_tags[] = 'script';

			return $allowed_tags;
		} );

		$upload_response = $this->handle_upload( $this->svg_path );
		$uploaded_svg    = file_get_contents( $upload_response['file'] );
		unlink( $upload_response['file'] );

		$this->assertArrayHasKey( 'file', $upload_response );
		$this->assertArrayNotHasKey( 'error', $upload_response );
		$this->assertContains( '<script ', $uploaded_svg );
	}

	/** @test */
	public function should_strip_disallowed_svg_attributes() {
		$this->make_instance()->add_svg_upload();

		add_filter( 'tribe_svg_allowed_attributes', function ( $allowed_attributes ) {
			return array_diff( $allowed_attributes, [ 'style' ] );
		} );

		$upload_response = $this->handle_upload( $this->svg_path );
		$uploaded_svg    = file_get_contents( $upload_response['file'] );
		unlink( $upload_response['file'] );

		$this->assertArrayHasKey( 'file', $upload_response );
		$this->assertArrayNotHasKey( 'error', $upload_response );
		$this->assertNotContains( 'style=', $uploaded_svg );
	}

	/** @test */
	public function should_keep_allowed_svg_attributes() {
		$this->make_instance()->add_svg_upload();

		add_filter( 'tribe_svg_allowed_attributes', function ( $allowed_attributes ) {
			$allowed_attributes[] = 'style';

			return $allowed_attributes;
		} );

		$upload_response = $this->handle_upload( $this->svg_path );
		$uploaded_svg    = file_get_contents( $upload_response['file'] );
		unlink( $upload_response['file'] );

		$this->assertArrayHasKey( 'file', $upload_response );
		$this->assertArrayNotHasKey( 'error', $upload_response );
		$this->assertContains( 'style=', $uploaded_svg );
	}
}