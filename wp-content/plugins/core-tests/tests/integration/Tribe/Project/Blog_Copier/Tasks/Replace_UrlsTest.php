<?php

namespace Tribe\Project\Blog_Copier\Tasks;

use Tribe\Project\Blog_Copier\Copy_Configuration;
use Tribe\Project\Blog_Copier\Copy_Manager;

class Replace_UrlsTest extends \Codeception\TestCase\WPTestCase {

	public function test_replaces_upload_urls() {
		/** @var \wpdb $wpdb */
		global $wpdb;

		$src  = 'src';
		$dest = 'dest';

		/** @var \WP_User $user */
		$user = $this->factory()->user->create_and_get();

		$current_site = get_current_site();
		if ( is_subdomain_install() ) {
			$src_domain = sprintf( '%s.%s', $src, $current_site->domain );
			$src_path   = '/';
		} else {
			$src_domain = $current_site->domain;
			$src_path   = sprintf( '/%s/', $src );
		}

		$suppress = $wpdb->suppress_errors();
		$src_id   = wpmu_create_blog( $src_domain, $src_path, 'Source Blog', $user->ID, [ "public" => 1 ], $current_site->id );
		$wpdb->suppress_errors( $suppress );

		$post_content_template = '<img src="%s" alt="" />';
		$panel_template        = [
			'panels' => [
				[
					'type'   => 'imagetext',
					'depth'  => 0,
					'data'   => [
						'title'       => '',
						'layout'      => 'image-left',
						'description' => '<img src="%s" alt="" />',
						'image'       => 0,
						'cta'         => [
							'url'    => '',
							'target' => '_self',
							'label'  => 'CTA label',
						],
					],
					'panels' => [],
				],
			],
		];

		switch_to_blog( $src_id );

		$src_uploads   = wp_upload_dir( null, false, true );
		$src_image_url = trailingslashit( $src_uploads[ 'url' ] ) . 'image.png';
		$src_panel     = $panel_template;

		$src_panel[ 'panels' ][ 0 ][ 'data' ][ 'description' ]  = sprintf( $src_panel[ 'panels' ][ 0 ][ 'data' ][ 'description' ], $src_image_url );
		$src_panel[ 'panels' ][ 0 ][ 'data' ][ 'cta' ][ 'url' ] = home_url();

		$content_post_id = $this->factory()->post->create( [
			'post_type'             => 'post',
			'post_status'           => 'publish',
			'post_content'          => sprintf( $post_content_template, $src_image_url ),
			'post_content_filtered' => wp_slash( \json_encode( $src_panel ) ),
		] );

		restore_current_blog();

		$config  = new Copy_Configuration( [
			'src'     => $src_id,
			'address' => $dest,
			'title'   => 'Copy Destination',
			'files'   => true,
			'notify'  => '',
			'user'    => $user->ID,
		] );
		$post_id = $this->factory()->post->create( [
			'post_type'    => Copy_Manager::POST_TYPE,
			'post_status'  => 'publish',
			'post_content' => \json_encode( $config ),
		] );

		$create = new Create_Blog();
		$create->handle( [
			'post_id' => $post_id,
		] );

		$destination_blog = get_post_meta( $post_id, Copy_Manager::DESTINATION_BLOG, true );

		$tables = new Replace_Tables();
		$tables->handle( [
			'post_id' => $post_id,
		] );

		$urls = new Replace_Urls();
		$urls->handle( [
			'post_id' => $post_id,
		] );

		switch_to_blog( $destination_blog );

		$content_post   = get_post( $content_post_id );
		$dest_uploads   = wp_upload_dir( null, false, true );
		$dest_image_url = trailingslashit( $dest_uploads[ 'url' ] ) . 'image.png';

		$dest_content = sprintf( $post_content_template, $dest_image_url );
		$dest_panel   = $panel_template;

		$dest_panel[ 'panels' ][ 0 ][ 'data' ][ 'description' ]  = sprintf( $dest_panel[ 'panels' ][ 0 ][ 'data' ][ 'description' ], $dest_image_url );
		$dest_panel[ 'panels' ][ 0 ][ 'data' ][ 'cta' ][ 'url' ] = home_url();

		restore_current_blog();

		$this->assertEquals( $dest_content, $content_post->post_content );
		$this->assertEquals( \json_encode( $dest_panel ), $content_post->post_content_filtered );
	}

}