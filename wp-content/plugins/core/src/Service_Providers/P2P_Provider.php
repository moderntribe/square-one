<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\P2P\Admin_Search_Filtering;
use Tribe\Project\P2P\Event_Query_Filters;
use Tribe\Project\P2P\Panel_Search_Filters;
use Tribe\Project\P2P\Query_Optimization;
use Tribe\Project\P2P\Relationships\General_Relationship;
use Tribe\Project\P2P\Titles_Filter;
use Tribe\Project\Post_Types\Event\Event;
use Tribe\Project\Post_Types\Page\Page;
use Tribe\Project\Post_Types\Post\Post;

/**
 * Class P2P_Provider
 *
 * Load configuration common to all sites
 */
class P2P_Provider implements ServiceProviderInterface {
	/**
	 * @var array P2P relationships to register
	 *            Keys should be the name of a Relationship subclass in
	 *            the namespace \Tribe\Project\P2P\Relationships
	 *            Values should be associative arrays, with keys "from"
	 *            and "to", each containing an array of Post Type classes
	 */
	protected $p2p_relationships = [
		General_Relationship::class => [
			'from' => [
				Page::NAME,
				Post::NAME,
				Event::NAME,
			],
			'to'   => [
				Page::NAME,
				Post::NAME,
				Event::NAME,
			],
		],
	];

	public function register( Container $container ) {
		$this->relationships( $container );
		$this->filters( $container );
	}

	protected function relationships( Container $container ) {

		foreach ( $this->p2p_relationships as $relationship => $sides ) {
			$container[ 'p2p.' . $relationship::NAME ] = function ( $container ) use ( $relationship, $sides ) {

				$from = $this->post_type_is_user( $sides[ 'from' ] ) ? 'user' : array_filter( $sides[ 'from' ], [
					$this,
					'is_registered_post_type',
				] );
				$to   = $this->post_type_is_user( $sides[ 'to' ] ) ? 'user' : array_filter( $sides[ 'to' ], [
					$this,
					'is_registered_post_type',
				] );

				return new $relationship( $from, $to );
			};

			add_action( 'init', function () use ( $container, $relationship ) {
				$container[ 'p2p.' . $relationship::NAME ]->hook();
			}, 11, 0 );
		}
	}

	protected function filters( Container $container ) {

		$container[ 'p2p.admin_titles_filter' ] = function ( $container ) {
			return new Titles_Filter( [ General_Relationship::NAME ] );
		};

		add_action( 'init', function () use ( $container ) {
			$container[ 'p2p.admin_titles_filter' ]->hook();
		}, 10, 0 );

		$container[ 'p2p.event_query_filters' ] = function ( $container ) {
			return new Event_Query_Filters();
		};

		add_action( 'tribe_events_pre_get_posts', function ( $query ) use ( $container ) {
			$container[ 'p2p.event_query_filters' ]->remove_event_filters_from_p2p_query( $query );
		}, 10, 1 );

		add_action( 'wp_ajax_posts-field-p2p-options-search', function () use ( $container ) {
			$container[ 'p2p.event_query_filters' ]->remove_event_filters_from_panel_p2p_requests();
		}, 10, 0 );

		$container[ 'p2p.panel_search_filters' ] = function ( $container ) {
			return new Panel_Search_Filters();
		};

		add_action( 'wp_ajax_posts-field-p2p-options-search', function () use ( $container ) {
			$container[ 'p2p.panel_search_filters' ]->set_p2p_search_filters();
		}, 0, 0 );

		$container[ 'p2p.query_optimization' ] = function ( $container ) {
			return new Query_Optimization();
		};

		add_action( 'p2p_init', function () use ( $container ) {
			$container[ 'p2p.query_optimization' ]->p2p_init();
		}, 10, 0 );

		foreach ( [ General_Relationship::NAME ] as $relationship ) {
			$container[ 'p2p.admin_search_filtering.' . $relationship ] = function ( $container ) use ( $relationship ) {
				return new Admin_Search_Filtering( $container[ 'p2p.' . $relationship ], 'both', $container[ 'assets' ] );
			};

			add_action( 'load-post.php', function () use ( $container, $relationship ) {
				$container[ 'p2p.admin_search_filtering.' . $relationship ]->add_post_page_hooks();
			}, 10, 0 );

			add_action( 'load-post-new.php', function () use ( $container, $relationship ) {
				$container[ 'p2p.admin_search_filtering.' . $relationship ]->add_post_page_hooks();
			}, 10, 0 );

			add_filter( 'p2p_connectable_args', function ( $query_vars, $connection, $post ) use ( $container, $relationship ) {
				$container[ 'p2p.admin_search_filtering.' . $relationship ]->filter_connectable_query_args( $query_vars, $connection, $post );
			}, 10, 3 );
		}


	}

	private function post_type_is_user( $side ) {
		return ( in_array( 'User', $side ) || in_array( 'user', $side ) );
	}

	/**
	 * Registering a p2p connection type with an unregistered post type
	 * will throw an error.
	 *
	 * @param string $post_type
	 *
	 * @return bool Whether the post type is registered
	 */
	private function is_registered_post_type( $post_type ) {
		return ! empty( get_post_type_object( $post_type ) );
	}
}
