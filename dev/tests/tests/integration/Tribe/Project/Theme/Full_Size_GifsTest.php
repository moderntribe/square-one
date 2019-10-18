<?php

namespace Tribe\Project\Theme;

use Tribe\Tests\Test_Case;

/**
 * Class Full_Size_GifsTest
 *
 * @package Tribe\Project\Theme
 */
class Full_Size_GifsTest extends Test_Case {

	public function test_full_size_only_gif_src() {
		$full_size_gif = new Full_Size_Gif();
		add_filter( 'image_downsize', [ $full_size_gif, 'full_size_only_gif' ], 10, 3 );

		$filename       = codecept_data_dir( 'test.gif' );

		$attachment_id = $this->factory()->attachment->create_upload_object( codecept_data_dir( 'test.gif' ) );

		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attach_data = wp_generate_attachment_metadata( $attachment_id, $filename );
		wp_update_attachment_metadata( $attachment_id, $attach_data );

		$full_size_src = wp_get_attachment_image_src( $attachment_id, 'full' );
		$thumbnail_src = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );

		remove_filter( 'image_downsize', [ $full_size_gif, 'full_size_only_gif' ], 10 );
		wp_delete_attachment( $attachment_id, true );

		$this->assertSame( $full_size_src, $thumbnail_src );
	}

}
