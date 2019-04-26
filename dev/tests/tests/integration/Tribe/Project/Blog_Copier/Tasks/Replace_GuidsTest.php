<?php

namespace Tribe\Project\Blog_Copier\Tasks;

use Tribe\Project\Blog_Copier\Copy_Configuration;
use Tribe\Project\Blog_Copier\Copy_Manager;
use Tribe\Tests\SquareOneTestCase;

class Replace_GuidsTest extends SquareOneTestCase {

	/**
	 * @env multisite
	 */
	public function test_replaces_guids() {
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

		$dest_prefix = $wpdb->get_blog_prefix( $destination_blog );

		$src_url  = get_site_url( $src_id );
		$dest_url = get_site_url( $destination_blog );

		$before_src_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$dest_prefix}posts WHERE guid LIKE %s", $wpdb->esc_like( $src_url ) . '%' ) );
		$before_dest_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$dest_prefix}posts WHERE guid LIKE %s", $wpdb->esc_like( $dest_url ) . '%' ) );

		$guids = new Replace_Guids();
		$guids->handle( [
			'post_id' => $post_id,
		] );

		$after_src_count  = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$dest_prefix}posts WHERE guid LIKE %s", $wpdb->esc_like( $src_url ) . '%' ) );
		$after_dest_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$dest_prefix}posts WHERE guid LIKE %s", $wpdb->esc_like( $dest_url ) . '%' ) );

		$this->assertEquals( 0, $before_dest_count );
		$this->assertEquals( 0, $after_src_count );
		$this->assertEquals( $before_src_count, $after_dest_count );
	}

}