<?php

namespace Tribe\Project\Blog_Copier\Tasks;

use Tribe\Project\Blog_Copier\Copy_Configuration;
use Tribe\Project\Blog_Copier\Copy_Manager;
use Tribe\Tests\Test_Case;

class Replace_OptionsTest extends Test_Case {

	/**
	 * @env multisite
	 */
	public function test_replaces_options() {
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

		$src_prefix  = $wpdb->get_blog_prefix( $src_id );
		$dest_prefix = $wpdb->get_blog_prefix( $destination_blog );

		update_blog_option( $destination_blog, $src_prefix . 'copy_test', 'testing' );

		$options = new Replace_Options();
		$options->handle( [
			'post_id' => $post_id,
		] );

		$this->assertEmpty( get_blog_option( $destination_blog, $src_prefix . 'copy_test', false ) );
		$this->assertEquals( 'testing', get_blog_option( $destination_blog, $dest_prefix . 'copy_test', false ) );

	}

}