<?php

namespace Tribe\Project\Theme;

use Tribe\Project\Templates\Component_Factory;
use Tribe\Project\Templates\Components\Image;
use Tribe\Project\Theme\Media\Full_Size_Gif;
use Tribe\Tests\Test_Case;
use Twig\Environment;

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
		$parent_post_id = wp_insert_post( [ 'title' => 'test_gifs', 'post_type' => 'post' ] );
		$attachment_id = self::factory()->attachment->create_upload_object( $filename, $parent_post_id );

		$attachment = \Tribe\Project\Templates\Models\Image::factory( $attachment_id );

		$factory        = new Component_Factory( tribe_project()->container()->get( Environment::class ) );
		$full_size      = $factory->get( Image::class, [
			Image::ATTACHMENT => $attachment,
			Image::SRC_SIZE   => 'full',
		] );
		$full_size_html = $full_size->render();
		$xml            = simplexml_load_string( $full_size_html );
		$full_size_src  = (string) $xml->img[0]['data-src'];

		$thumbnail      = $factory->get( Image::class, [
			Image::ATTACHMENT => $attachment,
			Image::SRC_SIZE   => 'thumbnail',
		] );
		$thumbnail_html = $thumbnail->render();
		$xml            = simplexml_load_string( $thumbnail_html );
		$thumbnail_src  = (string) $xml->img[0]['data-src'];

		wp_delete_attachment( $attachment_id, true );
		remove_filter( 'image_downsize', [ $full_size_gif, 'full_size_only_gif' ], 10 );

		$this->assertSame( $full_size_src, $thumbnail_src );
	}

}
