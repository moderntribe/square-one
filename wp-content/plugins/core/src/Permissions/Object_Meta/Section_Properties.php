<?php


namespace Tribe\Project\Permissions\Object_Meta;


use Tribe\Libs\ACF;
use Tribe\Project\Permissions\Section_Switcher;

class Section_Properties extends ACF\ACF_Meta_Group {
	const NAME = 'section_properties';

	const COLOR    = 'section_color';
	const NAV_MENU = 'section_nav_menu';

	public function register_group() {
		parent::register_group();
		add_filter( "acf/load_field/name=" . self::NAV_MENU, [ $this, 'load_nav_menu_choices' ], 10, 1 );
	}


	public function get_keys() {
		return [
			self::COLOR,
		];
	}

	public function get_group_config() {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Section Properties', 'tribe' ) );

		$group->add_field( $this->get_color_field() );
		$group->add_field( $this->get_nav_menu_field() );

		return $group->get_attributes();
	}

	private function get_color_field() {
		$field = new ACF\Field( self::NAME . '_' . self::COLOR );
		$field->set_attributes( [
			'label' => __( 'Color', 'tribe' ),
			'name'  => self::COLOR,
			'type'  => 'color_picker',
		] );

		return $field;
	}

	private function get_nav_menu_field() {
		$field = new ACF\Field( self::NAME . '_' . self::NAV_MENU );
		$field->set_attributes( [
			'label'      => __( 'Navigation Menu', 'tribe' ),
			'name'       => self::NAV_MENU,
			'type'       => 'select',
			'choices'    => [], // lazy-loaded later in load_nav_menu_choices()
			'allow_null' => true,
			'multiple'   => false,
		] );

		return $field;
	}

	/**
	 * Get nav menus that can be assigned to the section
	 *
	 * @param array $field
	 *
	 * @return array The updated field array with populated choices
	 */
	public function load_nav_menu_choices( $field ) {
		$menus = $this->get_available_nav_menus();

		$field[ 'choices' ] = array_combine( array_column( $menus, 'term_id' ), array_column( $menus, 'name' ) );

		return $field;
	}

	/**
	 * @return \WP_Term[]
	 */
	private function get_available_nav_menus() {
		return array_filter( wp_get_nav_menus(), function ( \WP_Term $menu ) {
			$term_key     = 'term_' . $menu->term_id;
			$availability = get_field( Menu_Sections::AVAILABILITY, $term_key, false );
			if ( $availability === Menu_Sections::AVAILABILITY_GLOBAL ) {
				return true;
			}
			$sections        = get_field( Menu_Sections::SECTIONS, $term_key, false );
			$current_section = get_user_option( Section_Switcher::FIELD_NAME, get_current_user_id() );
			if ( is_array( $sections ) && in_array( $current_section, $sections ) ) {
				return true;
			}

			return false;
		} );
	}
}