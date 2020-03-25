<?php
declare( strict_types=1 );

namespace Tribe\Project\P2P;

use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Subscriber_Interface;

class P2P_Subscriber implements Subscriber_Interface {
	public function register( ContainerInterface $container ): void {
		$this->relationships( $container );
		$this->filters( $container );
	}

	private function relationships( ContainerInterface $container ): void {
		add_action( 'p2p_init', function () use ( $container ) {
			foreach ( $container->get( P2P_Definer::RELATIONSHIPS ) as $relationship ) {
				$relationship->register();
			}
		}, 11, 0 );
	}

	protected function filters( ContainerInterface $container ) {

		add_action( 'init', function () use ( $container ) {
			$container->get( Titles_Filter::class )->hook();
		}, 10, 0 );


		add_action( 'tribe_events_pre_get_posts', function ( $query ) use ( $container ) {
			$container->get( Event_Query_Filters::class )->remove_event_filters_from_p2p_query( $query );
		}, 10, 1 );

		add_action( 'wp_ajax_posts-field-p2p-options-search', function () use ( $container ) {
			$container->get( Event_Query_Filters::class )->remove_event_filters_from_panel_p2p_requests();
		}, 10, 0 );

		add_action( 'wp_ajax_posts-field-p2p-options-search', function () use ( $container ) {
			$container->get( Panel_Search_Filters::class )->set_p2p_search_filters();
		}, 0, 0 );

		add_action( 'p2p_init', function () use ( $container ) {
			$container->get( Query_Optimization::class )->p2p_init();
		}, 10, 0 );


		add_action( 'load-post.php', function () use ( $container ) {
			foreach ( $container->get( P2P_Definer::ADMIN_FILTERS ) as $filter ) {
				$filter->add_post_page_hooks();
			}
		}, 10, 0 );
		add_action( 'load-post-new.php', function () use ( $container ) {
			foreach ( $container->get( P2P_Definer::ADMIN_FILTERS ) as $filter ) {
				$filter->add_post_page_hooks();
			}
		}, 10, 0 );

		add_filter( 'p2p_connectable_args', function ( $query_vars, $connection, $post ) use ( $container ) {
			foreach ( $container->get( P2P_Definer::ADMIN_FILTERS ) as $filter ) {
				$query_vars = $filter->filter_connectable_query_args( $query_vars, $connection, $post );
			}

			return $query_vars;
		}, 10, 3 );
	}
}
