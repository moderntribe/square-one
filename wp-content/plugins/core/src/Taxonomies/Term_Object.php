<?php


namespace Tribe\Project\Taxonomy;


use Tribe\Project\Object_Meta\Meta_Map;
use Tribe\Project\Object_Meta\Meta_Repository;

/**
 * Class Taxonomy_Object
 *
 * Extend this class for each registered taxonomy.
 * Be sure to set a value for the NAME constant in
 * each subclass.
 *
 * For most not-test usage, instances of this class
 * will be created via the `factory()` method called
 * on the subclasses.
 *
 * Instances can be used to access meta registered by
 * an appropriate Meta_Group, via the `get_meta()` method
 * called with a registered key.
 */
abstract class Term_Object {
	const NAME = '';

	/** @var Meta_Map */
	protected $meta;

	/** @var integer */
	protected $term_id = 0;

	/**
	 * Post_Object constructor.
	 *
	 * @param int           $term_id        The ID of a taxonomy term.
	 * @param Meta_Map|null $meta           Meta fields appropriate to this taxonomy term..
	 *                                      If you're not sure what to do here, chances
	 *                                      are you should be calling self::get_post().
	 */
	public function __construct( $term_id = 0, Meta_Map $meta = NULL ) {
		$this->term_id = $term_id;
		if ( isset( $meta ) ) {
			$this->meta = $meta;
		} else {
			$this->meta = new Meta_Map( static::NAME );
		}
	}

	public function __get( $key ) {
		return $this->get_meta( $key );
	}

	/**
	 * Get the value for the given meta key corresponding
	 * to this taxonomy term.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get_meta( $key ) {
		$term = sprintf( 'term_%s', $this->term_id );
		return $this->meta->get_value( $term, $key );
	}

	/**
	 * Get an instance of the Term_Object corresponding
	 * to the term with the given $term_id
	 *
	 * @param int $term_id The ID of an existing taxonomy term.
	 * @return static
	 */
	public static function factory( $term_id ) {
		/** @var Meta_Repository $meta_repo */
		$meta_repo = apply_filters( Meta_Repository::GET_REPO_FILTER, NULL );
		if ( !$meta_repo ) {
			$meta_repo = new Meta_Repository();
		}
		$taxonomy = static::NAME;
		if ( empty( $taxonomy ) ) {
			$term     = get_term( $term_id );
			$taxonomy = $term->taxonomy;
		}
		$term = new static( $term_id, $meta_repo->get( $taxonomy ) );

		return $term;
	}
}