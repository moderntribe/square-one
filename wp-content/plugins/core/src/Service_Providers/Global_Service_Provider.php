<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Tribe\Project\P2P\Admin_Search_Filtering;
use Tribe\Project\P2P\Event_Query_Filters;
use Tribe\Project\P2P\Panel_Search_Filters;
use Tribe\Project\P2P\Query_Optimization;
use Tribe\Project\P2P\Titles_Filter;

/**
 * Class Global_Service_Provider
 *
 * Load configuration common to all sites
 *
 * @package Tribe\Project\Service_Providers
 */
final class Global_Service_Provider extends Tribe_Service_Provider {

	protected $p2p_relationships = [
		'General_Relationship' => [
			'from' => [
				'Page',
			  'Post',
			  'Event',
			],
			'to' => [
				'Page',
				'Post',
				'Event',
			],
		],
	];

	protected $panels = [
		'Accordion',
		'CardGrid',
		'Gallery',
		'ImageText',
		'Interstitial',
		'MicroNav',
		'Wysiwyg',
		'LogoFarm',
		'Testimonial',
	];

	public function register( Container $container ) {
		parent::register( $container );
	}

	protected function p2p( Container $container ) {
		parent::p2p( $container );
		$container[ 'p2p.admin_titles_filter' ] = function( $container ) {
			$p2p_types = $this->map_p2p_classes_to_ids( [ 'General_Relationship' ], $container );
			return new Titles_Filter( $p2p_types );
		};
		add_action( 'init', function()  use ($container ) {
			$container[ 'p2p.admin_titles_filter' ]->hook();
		}, 10, 0 );

		$container[ 'p2p.event_query_filters' ] = function ( $container ) {
			return new Event_Query_Filters();
		};
		add_action( 'tribe_events_pre_get_posts', function( $query ) use ( $container ) {
			$container[ 'p2p.event_query_filters' ]->remove_event_filters_from_p2p_query( $query );
		}, 10, 1 );
		add_action( 'wp_ajax_posts-field-p2p-options-search', function( ) use ( $container ) {
			$container[ 'p2p.event_query_filters' ]->remove_event_filters_from_panel_p2p_requests(  );
		}, 10, 0 );

		$container[ 'p2p.panel_search_filters' ] = function ( $container ) {
			return new Panel_Search_Filters();
		};
		add_action( 'wp_ajax_posts-field-p2p-options-search', function() use ( $container ) {
			$container[ 'p2p.panel_search_filters' ]->set_p2p_search_filters();
		}, 0, 0 );

		$container[ 'p2p.query_optimization' ] = function ( $container ) {
			return new Query_Optimization();
		};
		add_action( 'p2p_init', function() use ( $container ) {
			$container[ 'p2p.query_optimization' ]->p2p_init();
		}, 10, 0 );

		$container[ 'p2p.admin_search_filtering.General_Relationship' ] = function( $container ) {
			return new Admin_Search_Filtering( $container[ 'p2p.General_Relationship' ], 'both', $container[ 'assets' ] );
		};
		add_action( 'load-post.php', function() use ( $container ) {
			$container[ 'p2p.admin_search_filtering.General_Relationship' ]->add_post_page_hooks();
		}, 10, 0 );
		add_action( 'load-post-new.php', function() use ( $container ) {
			$container[ 'p2p.admin_search_filtering.General_Relationship' ]->add_post_page_hooks();
		}, 10, 0 );
		add_filter( 'p2p_connectable_args', function( $query_vars, $connection, $post ) use ( $container ) {
			$container[ 'p2p.admin_search_filtering.General_Relationship' ]->filter_connectable_query_args( $query_vars, $connection, $post );
		}, 10, 3 );
	}
}
