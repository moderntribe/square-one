<?php


namespace Tribe\Project\Permissions\Object_Meta;


use Tribe\Libs\ACF;
use Tribe\Project\Permissions\Roles\Role_Collection;
use Tribe\Project\Permissions\Roles\Section_Role_Interface;
use Tribe\Project\Permissions\Taxonomies\Section\Section;
use Tribe\Project\Permissions\Users\User;

class User_Sections extends ACF\ACF_Meta_Group {

	const NAME = 'user_sections';

	const ROLES = 'user_roles';

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
			add_filter( 'acf/load_value/name=' . self::ROLES . '_' . $role->get_name(), function ( $value, $user_key, $field ) use ( $role ) {
				return $this->get_section_data_from_api( $value, $user_key, $field, $role );
			}, 10, 3 );
			add_filter( 'acf/update_value/name=' . self::ROLES . '_' . $role->get_name(), function ( $value, $user_key, $field ) use ( $role ) {
				return $this->store_section_data_via_api( $value, $user_key, $field, $role );
			}, 10, 3 );
		}
	}

	public function get_section_data_from_api( $value, $user_key, $field, Section_Role_Interface $role ) {
		$user_id = str_replace( 'user_', '', $user_key );
		$user    = new User( new \WP_User( $user_id ) );

		return $user->sections( $role->get_name() );
	}

	public function store_section_data_via_api( $value, $user_key, $field, Section_Role_Interface $role ) {
		$user_id     = str_replace( 'user_', '', $user_key );
		$wp_user     = new \WP_User( $user_id );
		$user        = new User( $wp_user );
		$section_ids = $user->sections( $role->get_name() );
		foreach ( array_diff( $section_ids, $value ) as $section_id ) {
			$section = Section::factory( $section_id );
			$section->delete_role( $wp_user );
		}
		foreach ( array_diff( $value, $section_ids ) as $section_id ) {
			$section = Section::factory( $section_id );
			$section->set_role( $wp_user, $role->get_name() );
		}

		return [];
	}


	public function get_keys() {
		return [];
	}

	public function get_group_config() {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Sections', 'tribe' ) );

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
			'type'       => 'taxonomy',
			'taxonomy'   => Section::NAME,
			'multiple'   => true,
			'field_type' => 'multi_select',
			'add_term'   => false,
			'allow_null' => true,
		] );
		if ( ! current_user_can( 'edit_users' ) ) {
			$field->set( 'disabled', 'true' );
		}

		return $field;
	}
}