<?php

namespace Tribe\Project\ElasticSearch;

/**
 * Rest_Api
 *
 *
 * @link    http://flashback.loc/wp-json/wp/v2/product/
 *
 * @example ?filter[search_by][meta][]=original_name&search=Famous
 *          ?filter[search_by][meta][]=_sku&search=488888
 *          ?filter[product_cat]=featured
 *
 *
 * @package Tribe\Project\ElasticSearch
 */
class Rest_Api {
	const SEARCH_FIELDS = 'search_by';


	public function hook(){
		add_filter( 'rest_query_vars', [ $this, 'allow_ep_rest_vars' ], 10 );
		add_filter( 'pre_get_posts', [ $this, 'limit_search_fields' ], 12 );
		add_filter( 'woocommerce_register_post_type_product', [ $this, 'allow_products_in_rest' ], 10 );
	}


	public function allow_ep_rest_vars( $vars ){
		$vars[] = self::SEARCH_FIELDS;

		return $vars;
	}


	/**
	 * ElasticPress adds many default fields to search_fields
	 * If we want to only search by a single field we have to override the
	 * query after they do.
	 *
	 * @see ep_improve_default_search()
	 * @see ep_wc_translate_args()
	 *
	 * @param \WP_Query $query
	 *
	 * @return \WP_Query
	 */
	public function limit_search_fields( \WP_Query $query ){
		if( $query->get( self::SEARCH_FIELDS ) ){
			$query->set( 'search_fields', $query->get( self::SEARCH_FIELDS ) );
		}

		return $query;
	}


	public function allow_products_in_rest( $args ){
		$args[ 'show_in_rest' ] = true;

		return $args;
	}
}