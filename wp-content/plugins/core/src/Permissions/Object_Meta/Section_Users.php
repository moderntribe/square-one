<?php


namespace Tribe\Project\Permissions\Object_Meta;


use Tribe\Libs\ACF;
use Tribe\Project\Permissions\Roles\Role_Collection;
use Tribe\Project\Permissions\Roles\Section_Role_Interface;
use Tribe\Project\Permissions\Taxonomies\Section\Section;

class Section_Users extends ACF\ACF_Meta_Group {

	const NAME = 'section_users';

	const ROLES = 'section_roles';

	/**
	 * @var Role_Collection
	 */
	private $roles = null;

	public function __construct( array $object_types, Role_Collection $roles ) {
		parent::__construct( $object_types );
		$this->roles = $roles;
	}

	public function register_group() {
		parent::register_group();

		foreach ( $this->roles->roles() as $role ) {
			add_filter( 'acf/load_value/name=' . self::ROLES . '_' . $role->get_name(), function ( $value, $section_key, $field ) use ( $role ) {
				return $this->get_user_data_from_api( $value, $section_key, $field, $role );
			}, 10, 3 );
			add_filter( 'acf/update_value/name=' . self::ROLES . '_' . $role->get_name(), function ( $value, $section_key, $field ) use ( $role ) {
				return $this->store_user_data_via_api( $value, $section_key, $field, $role );
			}, 10, 3 );
		}
	}

	public function get_user_data_from_api( $value, $section_key, $field, Section_Role_Interface $role ) {
		$section_id = str_replace( 'term_', '', $section_key );
		$section    = Section::factory( $section_id );

		return $section->get_users( $role->get_name() );
	}

	public function store_user_data_via_api( $value, $section_key, $field, Section_Role_Interface $role ) {
		$section_id = str_replace( 'term_', '', $section_key );
		$section    = Section::factory( $section_id );
		$users      = $section->get_users( $role->get_name() );

		foreach ( array_diff( $users, $value ) as $user_id ) {
			$section->delete_role( new \WP_User( $user_id ) );
		}
		foreach ( array_diff( $value, $users ) as $user_id ) {
			$section->set_role( new \WP_User( $user_id ), $role->get_name() );
		}

		return [];
	}


	public function get_keys() {
		return [];
	}

	public function get_group_config() {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Users', 'tribe' ) );

		foreach ( $this->roles->roles() as $role ) {
			$group->add_field( $this->get_role_field( $role ) );
		}

		return $group->get_attributes();
	}

	private function get_role_field( Section_Role_Interface $role ) {
		$field = new ACF\Field( self::NAME . '_' . self::ROLES . '_' . $role->get_name() );
		$field->set_attributes( [
			'label'      => $role->get_label(),
			'name'       => self::ROLES . '_' . $role->get_name(),
			'type'       => 'user',
			'multiple'   => true,
			'allow_null' => true,
		] );
		if ( ! current_user_can( 'edit_users' ) ) {
			$field->set( 'disabled', 'true' );
		}

		return $field;
	}
}