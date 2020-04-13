<?php
declare( strict_types=1 );

namespace Tribe\Project\P2P;

use Tribe\Libs\Container\Abstract_Subscriber;

class P2P_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		$this->relationships();
		$this->filters();
	}

	private function relationships(): void {
		add_action( 'p2p_init', function () {
			foreach ( $this->container->get( P2P_Definer::RELATIONSHIPS ) as $relationship ) {
				$relationship->register();
			}
		}, 11, 0 );
	}

	protected function filters() {

		add_action( 'init', function () {
			$this->container->get( Titles_Filter::class )->hook();
		}, 10, 0 );


		add_action( 'tribe_events_pre_get_posts', function ( $query ) {
			$this->container->get( Event_Query_Filters::class )->remove_event_filters_from_p2p_query( $query );
		}, 10, 1 );

		add_action( 'wp_ajax_posts-field-p2p-options-search', function () {
			$this->container->get( Event_Query_Filters::class )->remove_event_filters_from_panel_p2p_requests();
		}, 10, 0 );

		add_action( 'wp_ajax_posts-field-p2p-options-search', function () {
			$this->container->get( Panel_Search_Filters::class )->set_p2p_search_filters();
		}, 0, 0 );

		add_action( 'p2p_init', function () {
			$this->container->get( Query_Optimization::class )->p2p_init();
		}, 10, 0 );


		add_action( 'load-post.php', function () {
			foreach ( $this->container->get( P2P_Definer::ADMIN_FILTERS ) as $filter ) {
				$filter->add_post_page_hooks();
			}
		}, 10, 0 );
		add_action( 'load-post-new.php', function () {
			foreach ( $this->container->get( P2P_Definer::ADMIN_FILTERS ) as $filter ) {
				$filter->add_post_page_hooks();
			}
		}, 10, 0 );

		add_filter( 'p2p_connectable_args', function ( $query_vars, $connection, $post ) {
			foreach ( $this->container->get( P2P_Definer::ADMIN_FILTERS ) as $filter ) {
				$query_vars = $filter->filter_connectable_query_args( $query_vars, $connection, $post );
			}

			return $query_vars;
		}, 10, 3 );
	}
}
