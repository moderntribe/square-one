<?php


namespace Tribe\Project\Object_Meta;


/**
 * Class Meta_Map
 *
 * Maps requests for meta keys to the Meta_Group responsible for handling it
 */
class Meta_Map {
	private $object_type = '';

	/** @var Meta_Group[] */
	private $keys = [];

	public function __construct( $object_type ) {
		$this->object_type = $object_type;
	}

	/**
	 * Add the Meta_Group as the handler for its declared keys.
	 * Any keys that are already handled will be taken over by
	 * this group.
	 * 
	 * @param Meta_Group $group
	 * @return void
	 */
	public function add( Meta_Group $group ) {
		foreach ( $group->get_keys() as $key ) {
			$this->keys[ $key ] = $group;
		}
	}

	/**
	 * @return array All the keys that will be mapped
	 */
	public function get_keys() {
		return array_keys( $this->keys );
	}

	/**
	 * @param int $object_id
	 * @param string $key
	 * @return mixed The value for the given key
	 */
	public function get_value( $object_id, $key ) {
		if ( isset( $this->keys[ $key ] ) ) {
			return $this->keys[ $key ]->get_value( $object_id, $key );
		}
		return null;
	}
}