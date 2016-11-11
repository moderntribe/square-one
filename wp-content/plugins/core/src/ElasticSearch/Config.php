<?php

namespace Tribe\Project\ElasticSearch;

use Tribe\Project\Cron\Tasks\Re_Index;

/**
 * Config
 *
 * Various configuration overrides for ElasticPress
 *
 * @package Tribe\Project\ElasticSearch
 */
class Config {
	const INDEX_SUFFIX_OPTION = 'fb_es_index_name';


	public function hook(){
		add_filter( 'ep_index_name', [ $this, 'append_namespace_to_index' ] );
		add_filter( 'ep_active_modules', [ $this, 'activate_default_modules' ] );

		//query adjustments
		add_filter( 'posts_request', [ $this, 'fix_query_search_args_for_p2p' ], 9, 2 );
		add_action( 'pre_get_posts', [ $this, 'remove_es_from_woo_p2p_queries' ], 10, 1 );
		add_filter( 'ep_skip_query_integration', [ $this, 'remove_es_from_p2p_queries' ], 99, 2 );
	}


	/**
	 * The woo module has it's own filter on pre_get_posts which does not
	 * honor the baked in ep_skip_query_integration filter so we must
	 * remove the action which filters the query within the woo module.
	 *
	 * @param \WP_Query $query
	 *
	 * @return void
	 */
	public function remove_es_from_woo_p2p_queries( \WP_Query $query ){
		if( !empty( $query->_p2p_query ) || !empty( $query->_p2p_capture ) ){
			remove_action( 'pre_get_posts', 'ep_wc_translate_args', 11 );
		}
	}


	/**
	 * Turn off es queries which are P2P and allow P2P to do it's
	 * own thing. Allowing es to run during P2P queries causes
	 * all sorts of problems like broken related meta boxes.
	 *
	 * @param bool      $_skip
	 * @param \WP_Query $query
	 *
	 * @return bool
	 */
	public function remove_es_from_p2p_queries( $_skip, \WP_Query $query ){
		if( !empty( $query->_p2p_query ) || !empty( $query->_p2p_capture ) ){
			return true;
		}

		return $_skip;
	}


	/**
	 * Some queries get picked up by es but the search fields
	 * are set to 'default'
	 * ElasticPress expects an array for the search_fields key
	 * We give it what it expects
	 *
	 * @param string    $request
	 * @param \WP_Query $query
	 *
	 * @return string
	 */
	public function fix_query_search_args_for_p2p( $request, \WP_Query $query ){
		if( !empty( $query->get( 'search_fields' ) ) && $query->get( 'search_fields' ) == 'default' ){
			$query->set( 'search_fields', [] );

		}

		return $request;
	}


	public function activate_default_modules( $modules ){
		$modules[] = 'woocommerce';
		$modules[] = 'search';

		return array_unique( $modules );
	}


	public function append_namespace_to_index( $index_name ){
		if( defined( 'EP_NAMESPACE' ) ){
			$index_name .= '_' . EP_NAMESPACE;
		}
		$index_name .= '_' . $this->get_current_index_suffix();

		return $index_name;
	}


	public function get_current_index_suffix(){
		return get_site_option( self::INDEX_SUFFIX_OPTION );
	}


	public function update_current_index_suffix( $suffix ){
		unset( $_POST[ Re_Index::MANUAL_INDEX_KEY ] );
		if( function_exists( 'ep_delete_index' ) ){
			ep_delete_index();
		}
		update_site_option( self::INDEX_SUFFIX_OPTION, $suffix );
	}


	/**
	 *
	 * @static
	 *
	 * @return Config
	 */
	public static function instance(){
		return tribe_project()->container()[ 'search.config' ];
	}
}