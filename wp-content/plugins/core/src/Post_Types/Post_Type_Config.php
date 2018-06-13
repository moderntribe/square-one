<?php


namespace Tribe\Project\Post_Types;

use Tribe\Project\Post_Types\Meta_Box_Handlers\Meta_Box_Handler_Interface;

abstract class Post_Type_Config {
	/** @var string */
	protected $post_type = '';

	/**
	 * @param string $post_type The post type ID
	 */
	public function __construct( $post_type = '' ) {
		if ( $post_type ) {
			$this->post_type = $post_type;
		}
	}

	/**
	 * Hook into WordPress to register the post type
	 */
	public function register() {
		Post_Type_Registration::register( $this, apply_filters( Meta_Box_Handler_Interface::INSTANCE_FILTER, null ) );
	}

	/**
	 * @return string The ID of the post type
	 */
	public function post_type() {
		return $this->post_type;
	}


	/**
	 * Get the arguments for post type registration
	 *
	 * @return array
	 * @see \register_extended_post_type
	 */
	abstract public function get_args();

	/**
	 * Get the names to use for the post type
	 * @return array An associative array of labels
	 *               - singular
	 *               - plural
	 *               - slug
	 * @see \register_extended_post_type
	 */
	abstract public function get_labels();

	/**
	 * Get the metabox configuration for the post type
	 *
	 * @return array
	 * @see \CMB2
	 */
	public function get_meta_boxes() {
		return [];
	}

	/**
	 * Get the ACF metabox configuration for the post type
	 * @return array An array of field group configurations
	 *               (i.e., an array of arrays).
	 * @see acf_add_local_field_group()
	 */
	public function get_acf_fields() {
		return [];
	}
}