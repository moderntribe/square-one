<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Tribe\Project\Nav;
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

	protected $nav_menus = [
		'primary'   => 'Menu: Site',
		'secondary' => 'Menu: Footer',
	];

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
		'ContentGrid',
		'Gallery',
		'ImageText',
		'MicroNav',
		'Wysiwyg',
	];

	protected $post_types = [
		'Page',
		'Post',
		'Event',
		'Organizer',
		'Venue'
	];

	protected $taxonomies = [

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
		$container[ 'p2p.event_query_filters' ] = function ( $container ) {
			return new Event_Query_Filters();
		};
		$container[ 'p2p.panel_search_filters' ] = function ( $container ) {
			return new Panel_Search_Filters();
		};
		$container[ 'p2p.query_optimization' ] = function ( $container ) {
			return new Query_Optimization();
		};

		$container[ 'p2p.admin_search_filtering.General_Relationship' ] = function( $container ) {
			return new Admin_Search_Filtering( $container[ 'p2p.General_Relationship' ], 'both', $container[ 'assets' ] );
		};

		$container[ 'service_loader' ]->enqueue( 'p2p.admin_titles_filter', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'p2p.event_query_filters', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'p2p.panel_search_filters', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'p2p.query_optimization', 'hook' );
		$container[ 'service_loader' ]->enqueue( 'p2p.admin_search_filtering.General_Relationship', 'hook' );
	}
}
