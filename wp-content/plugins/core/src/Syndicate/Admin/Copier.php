<?php

namespace Tribe\Project\Syndicate\Admin;

use Tribe\Project\Syndicate\Admin\Contracts\Display;
use Tribe\Project\Syndicate\Blog;

class Copier extends Display {

	const COPY_ACTION   = 'copy_to_blog';
	const REMOVE_ACTION = 'remove_copied_post';

	public function do_copy_links( $post ) {
		$actions = [];

		if ( ! $this->object_in_blog( $post->ID ) ) {
			$actions['copy'] = $this->copy_to_blog_link( $post );
		}

		global $wpdb;
		$syndicated_tracking_table = Blog::SYNDICATED_TRACKING_TABLE;
		if ( $wpdb->get_var( $wpdb->prepare(
			"SELECT COUNT(*) FROM {$wpdb->base_prefix}{$syndicated_tracking_table} WHERE copied_post_id = %d AND blog_id = %d",
			$post->ID,
			$wpdb->blogid ) )
		) {
			$actions['remove'] = $this->remove_copied_post_link( $post );
		}

		return $actions;
	}

	/**
	 * @filter admin_post_copy_to_blog
	 */
	public function copy_to_blog() {
		if ( ! check_admin_referer( self::COPY_ACTION . $_GET['id'] ) ) {
			wp_die( __( 'Invalid referrer.', 'tribe') );
		}

		if ( ! filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ) ) {
			wp_die( __( 'Invalid id.', 'tribe') );
		}

		if ( $this->post_exists( $_GET['id'] ) ) {
			wp_die( __( 'Already copied.', 'tribe') );
		}

		define( 'DOING_COPY', true );



		switch_to_blog( BLOG_ID_CURRENT_SITE );
		$post = get_post( $_GET['id'], ARRAY_A );
		if ( ! $post ) {
			return;
		}
		$source_post_id = $post['ID'];
		$terms          = [];
		$taxonomies     = get_post_taxonomies( $_GET['id'] );
		foreach ( $taxonomies as $taxonomy ) {
			$terms = array_merge( wp_get_post_terms( $_GET['id'], $taxonomy ), $terms );
		}
		$meta = get_metadata( 'post', $_GET['id'] );
		restore_current_blog();

		unset ( $post['ID'], $post['guid'], $post['post_category'], $post['post_author'] );
		$id = wp_insert_post( $post );

		foreach ( $meta as $key => $values ) {
			$values = array_map( 'maybe_unserialize', $values );
			foreach ( $values as $value ) {
				$this->set_meta( $id, $key, $value );
			}
		}

		foreach ( $terms as $term ) {
			wp_set_object_terms( $id, $term->name, $term->taxonomy, true );
		}

		global $wpdb;
		$syndicated_tracking_table = Blog::SYNDICATED_TRACKING_TABLE;
		$wpdb->insert( $wpdb->base_prefix . $syndicated_tracking_table,
			[
				'source_post_id' => $source_post_id,
				'copied_post_id' => $id,
				'blog_id'        => get_current_blog_id(),
			]
		);

		wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
		exit;
	}

	protected function copy_to_blog_link( $post ) {
		return sprintf( '<a href=%s>%s</a>',
			wp_nonce_url(
				get_admin_url( get_current_blog_id(), 'admin-post.php?action=' . self::COPY_ACTION . '&id=' . $post->ID ),
				Copier::COPY_ACTION . $post->ID
			),
			esc_html__( 'Copy to local site', 'tribe' )
		);
	}

	protected function remove_copied_post_link( $post ) {
		return sprintf( '<a href=%s>%s</a>',
			wp_nonce_url(
				get_admin_url( get_current_blog_id(), 'admin-post.php?action=' . self::REMOVE_ACTION . '&id=' . $post->ID ),
				Copier::REMOVE_ACTION . $post->ID
			),
			__( 'Remove this copy', 'tribe' )
		);
	}

	protected function set_meta( $id, $key, $value ) {
		if ( $this->excluded_meta( $key ) ) {
			return;
		}

		add_post_meta(  $id, $key, $value );
	}

	/**
	 * @filter admin_post_remove_copied_post
	 */
	public function remove_copied_post() {
		if ( ! check_admin_referer( self::REMOVE_ACTION . $_GET['id'] ) ) {
			return;
		}

		wp_delete_post( $_GET['id'], true );

		global $wpdb;
		$syndicated_tracking_table = Blog::SYNDICATED_TRACKING_TABLE;
		$wpdb->delete( $wpdb->base_prefix . $syndicated_tracking_table,
			[
				'copied_post_id' => $_GET['id'],
				'blog_id'        => get_current_blog_id(),
			]
		);

		wp_safe_redirect( $_SERVER['HTTP_REFERER'] );
		exit;
	}

}