<?php
declare( strict_types=1 );

namespace Tribe\Project\Content;

use Psr\Container\ContainerInterface;
use Tribe\Project\Container\Subscriber_Interface;

class Content_Subscriber implements Subscriber_Interface {
	public function register( ContainerInterface $container ): void {
		$this->required_pages( $container );
	}

	private function required_pages( ContainerInterface $container ): void {
		add_action( 'admin_init', function () use ( $container ) {
			foreach ( $container->get( 'content.required_pages' ) as $page ) {
				$page->ensure_page_exists();
			}
		}, 10, 0 );
		add_action( 'trashed_post', function ( $post_id ) use ( $container ) {
			foreach ( $container->get( 'content.required_pages' ) as $page ) {
				$page->clear_option_on_delete( $post_id );
			}
		}, 10, 1 );
		add_action( 'deleted_post', function ( $post_id ) use ( $container ) {
			foreach ( $container->get( 'content.required_pages' ) as $page ) {
				$page->clear_option_on_delete( $post_id );
			}
		}, 10, 1 );
		add_action( 'acf/init', function () use ( $container ) {
			foreach ( $container->get( 'content.required_pages' ) as $page ) {
				$page->register_setting();
			}
		}, 10, 0 );
		add_filter( 'display_post_states', function ( $post_states, $post ) use ( $container ) {
			foreach ( $container->get( 'content.required_pages' ) as $page ) {
				$post_states = $page->indicate_post_state( $post_states, $post );
			}

			return $post_states;
		}, 10, 2 );
	}
}
