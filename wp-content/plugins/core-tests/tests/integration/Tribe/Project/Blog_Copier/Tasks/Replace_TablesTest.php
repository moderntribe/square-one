<?php

namespace Tribe\Project\Blog_Copier\Tasks;

use Tribe\Project\Blog_Copier\Copy_Configuration;
use Tribe\Project\Blog_Copier\Copy_Manager;

class Replace_TablesTest extends \Codeception\TestCase\WPTestCase {

	/**
	 * @env multisite
	 */
	public function test_replaces_tables() {
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

		switch_to_blog( $src_id );
		$this->factory()->post->create_many( 5, [
			'post_type'   => 'post',
			'post_status' => 'publish',
		] );
		$src_post_count = intval( $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts}" ) );
		restore_current_blog();

		$create = new Create_Blog();
		$create->handle( [
			'post_id' => $post_id,
		] );

		$destination_blog = get_post_meta( $post_id, Copy_Manager::DESTINATION_BLOG, true );

		$dest_prefix = $wpdb->get_blog_prefix( $destination_blog );
		$before_post_count = intval( $wpdb->get_var( "SELECT COUNT(*) FROM {$dest_prefix}posts" ) );

		$replace = new Replace_Tables();
		$replace->handle( [
			'post_id' => $post_id,
		] );

		$after_post_count = intval( $wpdb->get_var( "SELECT COUNT(*) FROM {$dest_prefix}posts" ) );

		$this->assertEquals( $src_post_count, $after_post_count );
		$this->assertNotEquals( $before_post_count, $after_post_count );

		$this->assertEquals( $config->get_title(), get_blog_option( $destination_blog, 'blogname' ) );
	}

}