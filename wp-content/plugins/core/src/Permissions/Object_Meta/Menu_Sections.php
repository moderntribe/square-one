<?php


namespace Tribe\Project\Permissions\Object_Meta;


use Tribe\Libs\ACF;
use Tribe\Project\Permissions\Taxonomies\Section\Section;

class Menu_Sections extends ACF\ACF_Meta_Group {

	const NAME = 'menu_sections';

	const SECTIONS             = 'menu_sections';
	const AVAILABILITY         = 'menu_availability';
	const AVAILABILITY_SECTION = 'section';
	const AVAILABILITY_GLOBAL  = 'global';

	public function get_keys() {
		return [
			self::SECTIONS,
		];
	}

	public function register_group() {
		parent::register_group();

		// hack to work around acf bug where fields
		// don't display if a menu has no items
		add_action( 'load-nav-menus.php', function () {
			if ( isset( $_REQUEST[ 'menu' ] ) ) {
				$GLOBALS[ 'acf_menu' ] = (int) $_REQUEST[ 'menu' ];
			} else {
				$recently_edited = absint( get_user_option( 'nav_menu_recently_edited' ) );
				if ( ! empty( $recently_edited ) ) {
					$GLOBALS[ 'acf_menu' ] = $recently_edited;
				}
			}
		} );
	}

	public function get_group_config() {
		$group = new ACF\Group( self::NAME, $this->object_types );
		$group->set( 'title', __( 'Sections', 'tribe' ) );
		$location = [
			[
				[
					'param'    => 'nav_menu',
					'operator' => '==',
					'value'    => 'all',
				],
			],
		];
		$group->set( 'location', $location );
		$group->set( 'style', 'seamless' );

		$group->add_field( $this->get_section_field() );
		$group->add_field( $this->get_availability_field() );

		return $group->get_attributes();
	}

	private function get_section_field() {
		$field = new ACF\Field( self::NAME . '_' . self::SECTIONS );
		$field->set_attributes( [
			'label'      => __( 'Sections', 'tribe' ),
			'name'       => self::SECTIONS,
			'type'       => 'taxonomy',
			'taxonomy'   => Section::NAME,
			'multiple'   => true,
			'field_type' => 'multi_select',
			'add_term'   => false,
			'allow_null' => true,
		] );
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			$field->set( 'disabled', 'true' );
		}

		return $field;
	}

	private function get_availability_field() {
		$field = new ACF\Field( self::NAME . '_' . self::AVAILABILITY );
		$field->set_attributes( [
			'label'         => __( 'Availability', 'tribe' ),
			'name'          => self::AVAILABILITY,
			'type'          => 'select',
			'multiple'      => false,
			'allow_null'    => false,
			'choices'       => [
				self::AVAILABILITY_SECTION => __( 'Selected Sections', 'tribe' ),
				self::AVAILABILITY_GLOBAL  => __( 'All Sections', 'tribe' ),
			],
			'default_value' => self::AVAILABILITY_SECTION,
		] );
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			$field->set( 'disabled', 'true' );
		}

		return $field;
	}

}