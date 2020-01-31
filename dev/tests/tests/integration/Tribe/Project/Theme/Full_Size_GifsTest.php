<?php

namespace Tribe\Project\Theme;

use Tribe\Project\Templates\Components\Image;

use Tribe\Tests\Test_Case;

/**
 * Class Full_Size_GifsTest
 * @package Tribe\Project\Theme
 */
class Full_Size_GifsTest extends Test_Case {

	public function test_full_size_only_gif_src() {
		$full_size_gif = new Full_Size_Gif();
		add_filter( 'image_downsize', [ $full_size_gif, 'full_size_only_gif' ], 10, 3 );

		$filename       = codecept_data_dir( 'test.gif' );
		$parent_post_id = wp_insert_post( [ 'title' => 'test_gifs', 'post_type' => 'post' ] );

		$filetype      = wp_check_filetype( basename( $filename ), null );
		$wp_upload_dir = wp_upload_dir();
		$attachment    = [
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		];
		$attachment_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attach_data = wp_generate_attachment_metadata( $attachment_id, $filename );
		wp_update_attachment_metadata( $attachment_id, $attach_data );

		$full_size      = Image::factory( [
			Image::IMG_ID   => $attachment_id,
			Image::SRC_SIZE => 'full',
		] );
		$full_size_html = $full_size->render();
		$xml            = simplexml_load_string( $full_size_html );
		$full_size_src  = (string) $xml->img[0]['data-src'];

		$thumbnail      = Image::factory( [
			Image::IMG_ID   => $attachment_id,
			Image::SRC_SIZE => 'thumbnail',
		] );
		$thumbnail_html = $thumbnail->render();
		$xml            = simplexml_load_string( $thumbnail_html );
		$thumbnail_src  = (string) $xml->img[0]['data-src'];

		$this->assertSame( $full_size_src, $thumbnail_src );

		remove_filter( 'image_downsize', [ $full_size_gif, 'full_size_only_gif' ], 10 );
	}

}
