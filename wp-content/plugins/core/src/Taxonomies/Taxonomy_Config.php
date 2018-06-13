<?php


namespace Tribe\Project\Taxonomies;

abstract class Taxonomy_Config {
	/** @var  string The ID of the taxonomy */
	protected $taxonomy = '';

	/** @var array Post types that support this taxonomy */
	protected $post_types = [];

	/**
	 * @var int
	 *
	 * The version of the taxonomy config. Increment to re-register default terms.
	 * Must be higher than 0, or no terms will be registered.
	 */
	protected $version = 0;

	/**
	 * @param string $taxonomy   The ID of the taxonomy
	 * @param array  $post_types An array of post types that will use this taxonomy
	 */
	public function __construct( $taxonomy = '', $post_types = [] ) {
		if ( $taxonomy ) {
			$this->taxonomy = $taxonomy;
		}
		if ( $post_types ) {
			$this->post_types = $post_types;
		}
	}

	/**
	 * Hook into WordPress to register the taxonomy
	 */
	public function register() {
		Taxonomy_Registration::register( $this );
	}

	/**
	 * @return string The ID of the taxonomy
	 */
	public function taxonomy() {
		return $this->taxonomy;
	}

	/**
	 * @return array The IDs of the post types associated with this taxonomy
	 */
	public function post_types() {
		return $this->post_types;
	}

	/**
	 * @return int The version of this taxonomy's schema (i.e., the initial terms to register)
	 */
	public function version() {
		return $this->version;
	}

	/**
	 * Get the arguments for taxonomy registration
	 *
	 * @return array
	 * @see \Extended_Taxonomy
	 */
	abstract public function get_args();

	/**
	 * Get the names to use for the taxonomy
	 * @return array An associative array of labels
	 *               - singular
	 *               - plural
	 *               - slug
	 */
	abstract public function get_labels();

	/**
	 * Get the initial terms to register for this taxonomy
	 *
	 * @return array An associative array where slug is the key and label is the value
	 */
	public function default_terms() {
		return [];
	}
}