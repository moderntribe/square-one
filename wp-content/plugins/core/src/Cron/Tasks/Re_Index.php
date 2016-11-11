<?php

namespace Tribe\Project\Cron\Tasks;

use Tribe\Project\ElasticSearch\Config;

/**
 * Re_Index
 *
 * Sends requests to mimic ajax to elasticpress to reindex everything
 * Upon completion it updates the index option to use the new one setup during
 * this type of index.
 *
 * Runs independent of the Admin re-index but does mostly the same thing
 *
 * @see \Tribe\Project\ElasticSearch\Re_Index
 *
 *
 * @package Tribe\Project\Cron\Tasks
 */
class Re_Index implements _Task_Interface {
	use _Task;

	const MANUAL_INDEX_KEY = 'core_running_manual_es_re_index';

	/**
	 * response
	 *
	 * @var \stdClass
	 */
	private $response;


	/**
	 * Sends a ajax request to elastic press to trigger
	 * a standard reindex.
	 * Done this way because there is not direct call for reindex
	 * built into that plugin.
	 *
	 * @return
	 */
	public function run_task(){
		$data = [
			'body'      => [
				'nonce'                => wp_create_nonce( 'ep_nonce' ),
				'action'               => 'ep_index',
				self::MANUAL_INDEX_KEY => true,
			],
			'sslverify' => false,
			'timeout'   => MINUTE_IN_SECONDS,
		];

		$response = wp_remote_post( admin_url( 'admin-ajax.php' ), $data );
		if( is_wp_error( $response ) ){
			return false;
		}

		$this->response = json_decode( wp_remote_retrieve_body( $response ) );
		if( empty( $this->response->success ) ){
			return false;
		}
		if( !empty( $this->response->data->start ) || 0 !== $this->response->data->found_posts ){
			return $this->run_task();
		}

		return true;
	}


	public function is_complete(){
		if( !empty( $this->response->data->site_stack ) ){
			return false;
		}
		if( 0 !== $this->response->data->found_posts ){
			return false;
		}

		Config::instance()->update_current_index_suffix( \Tribe\Project\ElasticSearch\Re_Index::instance()->get_suffix_key() );
		\Tribe\Project\ElasticSearch\Re_Index::instance()->clear_index_meta();

		return true;
	}


	/**
	 *
	 * @static
	 *
	 * @return Re_Index
	 */
	public static function instance(){
		return tribe_project()->container()[ 'cron.tasks.re_index' ];
	}

}