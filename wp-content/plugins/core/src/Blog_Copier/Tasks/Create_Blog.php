<?php


namespace Tribe\Project\Blog_Copier\Tasks;


use Tribe\Project\Blog_Copier\Copy_Configuration;
use Tribe\Project\Blog_Copier\Copy_Manager;
use Tribe\Project\Queues\Contracts\Task;

class Create_Blog implements Task {

	public function handle( array $args ): bool {
		/** @var \wpdb $wpdb */
		global $wpdb;

		$post_id = empty( $args[ 'post_id' ] ) ? 0 : absint( $args[ 'post_id' ] );

		$data   = \json_decode( get_post_field( 'post_content', $post_id, 'raw' ), true );
		$config = new Copy_Configuration( $data );

		$current_site = get_current_site();

		if ( is_subdomain_install() ) {
			$dest_domain = sprintf( '%s.%s', $config->get_address(), $current_site->domain );
			$dest_path   = '/';
		} else {
			$dest_domain = $current_site->domain;
			$dest_path   = sprintf( '/%s/', $config->get_address() );
		}

		// wpmu_create_blog throws mysql errors when running during a transaction
		$suppress = $wpdb->suppress_errors();
		$dest_id  = wpmu_create_blog( $dest_domain, $dest_path, $config->get_title(), $config->get_user(), [ "public" => 1 ], $current_site->id );
		$wpdb->suppress_errors( $suppress );

		if ( is_wp_error( $dest_id ) ) {
			$error = $dest_id;
			do_action( Copy_Manager::TASK_ERROR_ACTION, static::class, $args, $error );

			return true;
		}

		// WP forces http URL scheme on subdomain installations
		if ( is_subdomain_install() ) {
			$src_url     = get_site_url( $config->get_src() );
			$src_scheme  = parse_url( $src_url, PHP_URL_SCHEME );
			$dest_url    = get_site_url( $dest_id );
			$dest_scheme = parse_url( $dest_url, PHP_URL_SCHEME );
			if ( $src_scheme !== $dest_scheme ) {
				$dest_url = set_url_scheme( $dest_url, $src_scheme );
				update_blog_option( $dest_id, 'siteurl', $dest_url );
				update_blog_option( $dest_id, 'home', $dest_url );
			}
		}

		update_post_meta( $post_id, Copy_Manager::DESTINATION_BLOG, $dest_id );

		do_action( Copy_Manager::TASK_COMPLETE_ACTION, static::class, $args );

		return true;
	}

}