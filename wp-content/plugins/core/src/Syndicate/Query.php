<?php

namespace Tribe\Project\Syndicate;


class Query {

	protected $blog;

	public function __construct( Blog $blog ) {
		$this->blog = $blog;
	}

	/**
	 * @param $query
	 *
	 * @return string
	 * @filter query
	 */
	public function query( $query ) {
		if ( is_main_site() ) {
			return $query;
		}

		if ( $this->is_select_query( $query ) ) {
			return $this->alter_select_query( $query );
		}

		if ( $this->is_insert_query( $query ) ) {
			return $this->alter_insert_query( $query );
		}

		return $query;

	}

	protected function is_select_query( $query ) {
		return stripos( ltrim( $query ), 'SELECT' ) === 0;
	}

	protected function alter_select_query( $query ) {
		return str_replace( $this->blog->blog_table_names(), $this->blog->blog_view_names(), $query );
	}

	protected function is_insert_query( $query ) {
		return stripos( ltrim( $query ), 'INSERT' ) === 0;
	}

	protected function alter_insert_query( $query ) {
		if ( ! $this->relevant_insert( $query ) ) {
			return $query;
		}

		return $this->blog->enforce_insertion_id( $query, $this->relevant_insert( $query ) );
	}

	protected function relevant_insert( $query ) {
		$tables = array_filter( $this->blog->blog_table_names(), function( $table ) use ( $query ) {
			// If this query references a table we care about.
			return strripos( $query, $table ) !== false;
		}  );

		return reset( $tables );
	}

}