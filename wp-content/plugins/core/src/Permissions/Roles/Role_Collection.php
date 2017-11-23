<?php


namespace Tribe\Project\Permissions\Roles;


class Role_Collection {
	/** @var Section_Role_Interface[] */
	private $roles = [];

	public function __construct( Section_Role_Interface ...$roles ) {
		foreach ( $roles as $role ) {
			$this->roles[ $role->get_name() ] = $role;
		}
	}

	public function roles() {
		return $this->roles;
	}

	/**
	 * @param $name
	 *
	 * @return Section_Role_Interface
	 */
	public function get_role( $name ) {
		if ( ! isset( $this->roles[ $name ] ) ) {
			return new Null_Role();
		}

		return $this->roles[ $name ];
	}
}