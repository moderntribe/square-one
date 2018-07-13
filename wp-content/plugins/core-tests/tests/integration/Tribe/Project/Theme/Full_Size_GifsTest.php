<?php

namespace Tribe\Project\Theme;

use Tribe\Project\Theme\Full_Size_Gif;
use Tribe\Project\Theme\Image;

use Codeception\TestCase\WPTestCase;

/**
 * Class Full_Size_GifsTest
 * @package Tribe\Project\Theme
 */
class Full_Size_GifsTest extends WPTestCase {

	private $attachment_id;

	public function setUp() {
		// before
		parent::setUp();

		$filename = codecept_data_dir( 'test.gif' );
		$parent_post_id = wp_insert_post( [ 'title' => 'test_gifs', 'post_type' => 'post' ] );

		$filetype = wp_check_filetype( basename( $filename ), null );
		$wp_upload_dir = wp_upload_dir();
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$this->attachment_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attach_data = wp_generate_attachment_metadata( $this->attachment_id, $filename );
		wp_update_attachment_metadata( $this->attachment_id, $attach_data );
	}

	public function tearDown() {
		// your tear down methods here
		parent::tearDown();
	}

	public function test_full_size_only_gif_src() {
		$full_size = new Image( $this->attachment_id, [ 'src_size' => 'full' ] );
		$full_size_html = $full_size->render();
		$xml = simplexml_load_string( $full_size_html );
		$full_size_src = (string) $xml->img[0]['data-src'];

		$thumbnail = new Image( $this->attachment_id, [ 'src_size' => 'thumbnail' ] );
		$thumbnail_html = $thumbnail->render();
		$xml = simplexml_load_string( $thumbnail_html );
		$thumbnail_src = (string) $xml->img[0]['data-src'];

		$this->assertNotSame( $full_size_src, $thumbnail_src );

		$full_size_gif = new Full_Size_Gif();
		$filter_image_src = function( $src, $attachment_id ) use ( $full_size_gif ) {
			return $full_size_gif->full_size_only_gif_src( $src, $attachment_id );
		};
		add_filter( 'tribe_image_attributes_src', $filter_image_src, 10, 2 );

		$thumbnail_html = $thumbnail->render();
		$xml = simplexml_load_string( $thumbnail_html );
		$thumbnail_src = (string) $xml->img[0]['data-src'];
		remove_filter( 'tribe_image_attributes_src', $filter_image_src );

		$this->assertSame( $full_size_src, $thumbnail_src );
	}

}
