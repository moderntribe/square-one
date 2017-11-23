<?php


namespace Tribe\Project\Permissions\Capabilities;


use Tribe\Project\Permissions\Object_Meta\Menu_Sections;
use Tribe\Project\Permissions\Section_Switcher;

class Nav_Admin {

	/**
	 * @return void
	 * @action admin_menu
	 */
	public function filter_admin_menu() {
		$this->change_cap_for_nav_menus_menu_item();
	}

	/**
	 * Replace the Appearance -> Menus menu item to require the
	 * edit_nav_menus cap instead of edit_theme_options
	 *
	 * @return void
	 */
	private function change_cap_for_nav_menus_menu_item() {
		if ( current_user_can( 'edit_nav_menus' ) ) {
			global $_wp_submenu_nopriv;
			remove_submenu_page( 'themes.php', 'nav-menus.php' );
			add_theme_page( __( 'Menus' ), __( 'Menus' ), 'edit_nav_menus', 'nav-menus.php' );
			unset( $_wp_submenu_nopriv[ 'themes.php' ][ 'nav-menus.php' ] );
		}
	}

	/**
	 * Remove all theme locations so that user can't assign
	 *
	 * @return void
	 * @action load-nav-menus.php
	 * @action wp_ajax_add-menu-item
	 */
	public function deregister_theme_menu_locations() {
		if ( ! current_user_can( 'manage_options' ) ) {
			$GLOBALS[ '_wp_registered_nav_menus' ] = [];
			add_action( 'admin_print_styles', [ $this, 'print_css_to_hide_menu_locations_field' ], 10, 0 );
		}
	}

	public function print_css_to_hide_menu_locations_field() {
		?>
		<style type="text/css">
			.menu-theme-locations, .hide-if-no-customize {
				display: none;
			}
		</style>
		<?php
	}

	/**
	 * @param array    $user_caps
	 * @param array    $requested_caps
	 * @param array    $args
	 * @param \WP_User $user
	 *
	 * @return array The filters $user_caps array
	 * @filter user_has_cap
	 */
	public function filter_nav_menu_caps( $user_caps, $requested_caps, $args, $user ) {
		// this loads after admin menus have been established, so we don't need to worry about
		// granting inappropriate caps for other pages
		if ( $requested_caps == [ 'edit_theme_options' ] && is_admin() && empty( $user_caps[ 'edit_theme_options' ] ) ) {
			$user_caps[ 'edit_theme_options' ] = ! empty( $user_caps[ 'edit_nav_menus' ] );
		}

		return $user_caps;
	}

	public function filter_available_nav_menus( $menus, $args ) {
		if ( isset( $args[ 'fields' ] ) && $args[ 'fields' ] !== 'all' ) {
			return $menus;
		}
		$user            = wp_get_current_user();
		$current_section = get_user_option( Section_Switcher::FIELD_NAME, $user->ID );
		$can_admin       = $user->has_cap( 'edit_others_posts' );
		foreach ( $menus as $index => $m ) {
			if ( ! $m ) {
				unset( $menus[ $index ] );
				continue;
			}
			$sections = get_term_meta( $m->term_id, Menu_Sections::SECTIONS, true );
			if ( ! $can_admin && count( $sections ) > 1 ) {
				unset( $menus[ $index ] );
				continue;
			}
			if ( empty( $sections ) || ! in_array( $current_section, $sections ) ) {
				unset( $menus[ $index ] );
			}
		}

		return array_values( $menus );
	}

	public function filter_recently_edited_nav_menu_option( $menu_id ) {
		if ( empty( $menu_id ) ) {
			return $menu_id;
		}
		$current_section = get_user_option( Section_Switcher::FIELD_NAME, get_current_user_id() );
		$sections        = get_term_meta( $menu_id, Menu_Sections::SECTIONS, true );

		if ( empty( $sections ) || ! in_array( $current_section, $sections ) ) {
			return 0;
		}

		return $menu_id;
	}
}