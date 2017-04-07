<?php


namespace JSONLD;

/**
 * Class Page_Schema
 *
 * Gets data for the current page
 *
 * @package JSONLD
 */
class Page_Schema {
	private $object;

	public function __construct( $object = null ) {
		$this->object = $object ? $object : get_queried_object();
	}

	public function get_data() {
		$builder = $this->builder_factory();
		return $builder->get_data();
	}

	private function builder_factory() {
		if ( empty( $this->object ) ) {
			return new Null_Data_Builder( $this->object );
		}
		if ( $this->is_post() ) {
			return new Post_Data_Builder( $this->object );
		}
		if ( $this->is_taxonomy_term() ) {
			return new Term_Data_Builder( $this->object );
		}
		if ( $this->is_post_type_archive() ) {
			return new Post_Type_Data_Builder( $this->object );
		}
		if ( $this->is_author() ) {
			return new Author_Data_Builder( $this->object );
		}
		return new Null_Data_Builder( $this->object );
	}

	private function is_post() {
		return ( !empty( $this->object->ID ) && !empty( $this->object->post_type ) );
	}

	private function is_taxonomy_term() {
		return !empty( $this->object->term_id );
	}

	private function is_post_type_archive() {
		return ( !empty( $this->object->name ) && isset( $this->object->cap ) );
	}

	private function is_author() {
		return ( $this->object instanceof \WP_User );
	}
}