<?php

namespace Tribe\Project\ElasticSearch;

/**
 * Re_Index
 *
 * Fork the ElasticPress re-index process into an independent re-index that
 * runs when the version const is changed.
 *
 * Steals the process from ElasticPress via filters to keep things as separate
 * as possible to prevent conflicts.
 *
 * Because this may not finish all at one time it follows these steps:
 * 1. short circuit the options retrieval and updating to save our own meta
 * 2. create a new index name
 * 3. re-index everything by scheduling the re-index cron task
 *
 * Theoretically there should be no down time because the old index will load
 * for all non re-index related processes during the re-index.
 *
 * @see \Tribe\Project\Cron\Tasks\Re_Index
 *
 * @package Tribe\Project\ElasticSearch
 */
class Re_Index {

	const DB_OPTION = 'core_es_index_version';
	const DB_VERSION = 1;

	const PER_PAGE = 2000;
	const INDEX_SUFFIX_OPTION = 'core_es_temp_index_suffix';
	const INDEX_META_OPTION = 'core_es_reindex_meta';


	private function hooks(){
		if( !class_exists( 'EP_Dashboard' ) ){
			return;
		}
		//allow the cron to trigger the reindexes
		add_action( 'wp_ajax_nopriv_ep_index', [ \EP_Dashboard::factory(), 'action_wp_ajax_ep_index' ] );
		add_filter( 'ep_index_posts_per_page', [ $this, 'change_size_of_reindex_cue' ] );
		add_filter( 'ep_skip_index_reset', [ $this, 'skip_reset_during_reindex' ] );
		add_filter( 'ep_index_name', [ $this, 'get_temp_index_name' ] );
		add_filter( 'site_option_ep_index_meta', [ $this, 'get_current_index_meta' ] );
		add_action( 'update_site_option_ep_index_meta', [ $this, 'save_index_meta' ], 10, 2 );
		add_action( 'add_site_option_ep_index_meta', [ $this, 'save_index_meta' ], 10, 2 );

	}


	/**
	 * Save information about the current indexing process.
	 * This is injected later into the re-index process to prevent
	 * conflicts with the default indexing meta
	 *
	 * @param $_
	 * @param [] $value
	 *
	 * @return void
	 */
	public function save_index_meta( $_, $value ){
		if( $this->is_running_reindex() ){
			update_site_option( self::INDEX_META_OPTION, $value );
		}
	}


	/**
	 * Injects our index meta into the beginning of the re-index process.
	 * We store our own to prevent conflicts with default index meta.
	 * @param string $pre_option
	 *
	 * @return bool|[]
	 */
	public function get_current_index_meta( $default_meta ){
		if( $this->is_running_reindex() ){
			return get_site_option( self::INDEX_META_OPTION );
		}

		return $default_meta;

	}


	/**
	 * Use a temp index name during re-index to allow for completion
	 * before clearing the current index.
	 * When indexing is complete, this name then becomes the current index.
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	public function get_temp_index_name( $name ){
		if( $this->is_running_reindex() ){
			$existing = Config::instance()->get_current_index_suffix();
			if( !empty( $existing ) ){
				$name = str_replace( $existing, $this->get_suffix_key(), $name );
			} else {
				$name .= $this->get_suffix_key();
			}

		}

		return $name;
	}


	public function clear_index_meta(){
		delete_site_option( self::INDEX_META_OPTION );
	}


	public function get_suffix_key(){
		return get_site_option( self::INDEX_SUFFIX_OPTION );
	}


	/**
	 * If we are running a background reindex
	 * we skip the initial should_reset because we are going to swap
	 * out the index with the new one upon completion
	 *
	 * @param bool $should_reset
	 *
	 * @return bool
	 */
	public function skip_reset_during_reindex( $should_reset ){
		if( $this->is_running_reindex() ){
			return true;
		}

		return $should_reset;
	}


	/**
	 * Index more posts per round of re-index
	 *
	 * @param $per_page
	 *
	 * @return int
	 */
	public function change_size_of_reindex_cue( $per_page ){
		if( $this->is_running_reindex() ){
			return self::PER_PAGE;
		}

		return $per_page;
	}


	/**
	 * Is the current request running a background reindex?
	 *
	 * @return bool
	 */
	private function is_running_reindex(){
		return !empty( $_POST[ \Tribe\Project\Cron\Tasks\Re_Index::MANUAL_INDEX_KEY ] );

	}


	/**
	 * Add a re-index to our cron for later running
	 *
	 * @return void
	 */
	private function schedule_sync(){
		\Tribe\Project\Cron\Tasks\Re_Index::instance()->schedule();
	}


	/*** version and static *********************/

	private function run_updates(){
		update_site_option( self::INDEX_SUFFIX_OPTION, time() );
		$this->schedule_sync();

		update_site_option( self::DB_OPTION, self::DB_VERSION );

	}


	private function update_required(){
		$version = get_site_option( self::DB_OPTION, 0.1 );

		return ( version_compare( $version, self::DB_VERSION ) == - 1 );
	}


	public function init(){
		if( $this->update_required() ){
			$this->run_updates();
		}
		$this->hooks();

	}


	/**
	 *
	 * @static
	 *
	 * @return Re_Index
	 */
	public static function instance(){
		return tribe_project()->container()[ 'search.re_index' ];
	}

}