<?php


namespace Tribe\Project\Permissions;


use Tribe\Project\Permissions\Object_Meta\Default_Section;
use Tribe\Project\Permissions\Object_Meta\Section_Properties;
use Tribe\Project\Permissions\Taxonomies\Section\Section;
use Tribe\Project\Permissions\Users\User;

class Section_Switcher {
	const FIELD_NAME = 'tribe_current_section';
	const NONCE_NAME = 'set_section_nonce';
	const ACTION     = 'tribe_set_section';

	/**
	 * @return void
	 * @action admin_post_ . self::ACTION
	 */
	public function handle_request() {
		check_admin_referer( self::ACTION, self::NONCE_NAME ); // dies on failure
		$selection = $_POST[ self::FIELD_NAME ] ?? 0;
		if ( empty( $selection ) ) {
			return;
		}
		$section = Section::factory( $selection );
		$wp_user = wp_get_current_user();
		$role    = $section->get_role( $wp_user );
		if ( empty( $role ) && ! $wp_user->has_cap( 'edit_others_posts' ) ) {
			wp_die( __( 'You are not a member of this section. Please ask an administrator if you think you should have access', 'tribe' ) );
		}
		update_user_option( $wp_user->ID, self::FIELD_NAME, $selection );
		$redirect = $this->get_redirect_after_switch( $section, $_POST );
		wp_safe_redirect( $redirect );
		exit();
	}

	private function get_redirect_after_switch( Section $sction, $submission ) {
		if ( empty( $_POST[ '_wp_http_referer' ] ) ) {
			return admin_url();
		}
		$parsed = parse_url( $_POST[ '_wp_http_referer' ] );
		if ( empty( $parsed[ 'path' ] ) ) {
			return admin_url();
		}
		// if a single post admin, redirect to the list for that post type
		if ( $parsed[ 'path' ] == '/wp-admin/post.php' ) {
			parse_str( $parsed[ 'query' ], $query );
			$post_id = $query[ 'post' ] ?? 0;
			if ( $post_id ) {
				switch( get_post_type( $post_id ) ) {
					case 'post':
						return admin_url( 'edit.php' );
					default:
						return add_query_arg( [ 'post_type' => get_post_type( $post_id ) ], admin_url( 'edit.php' ) );
				}
			} else {
				return admin_url();
			}
		}
		return $_POST[ '_wp_http_referer' ];
	}

	/**
	 * @return void
	 * @action in_admin_header
	 */
	public function print_section_switcher() {
		$current_user = wp_get_current_user();
		$sections     = $this->get_section_list( $current_user );
		if ( empty( $sections ) ) {
			return; // nothing to render
		}
		$current_section = get_user_option( self::FIELD_NAME, $current_user->ID );
		$select          = $this->make_select_field( $sections, $current_section );
		$form            = $this->build_form( $select );
		echo $form;
	}

	/**
	 * @param \WP_User $current_user
	 *
	 * @return \WP_Term[]
	 */
	private function get_section_list( \WP_User $current_user ): array {
		$args = [
			'taxonomy'   => Section::NAME,
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		];
		if ( ! $current_user->has_cap( 'edit_others_posts' ) ) {
			$user        = new User( $current_user );
			$section_ids = $user->sections();
			if ( empty( $section_ids ) ) {
				return [];
			}
			$args[ 'include' ] = $section_ids;
		}

		return get_terms( $args );
	}

	/**
	 * @param \WP_Term[] $sections
	 * @param int        $selected
	 *
	 * @return string
	 */
	private function make_select_field( array $sections, $selected ): string {
		$field = sprintf( '<select name="%s" onchange="this.form.submit()">', esc_attr( self::FIELD_NAME ) );
		if ( empty( $selected ) ) {
			$field .= '<option value="">' . __( '-- Select a Section --', 'tribe' ) . '</option>';
		}
		foreach ( $sections as $term ) {
			$field .= sprintf(
				'<option value="%d" %s>%s</option>',
				$term->term_id,
				selected( $term->term_id, $selected, false ),
				esc_html( $term->name )
			);
		}
		$field .= '</select>';

		return $field;
	}

	private function build_form( string $select ): string {
		$form = sprintf(
			'<form id="tribe-section-switcher" method="post" action="%s">%s %s %s</form>',
			esc_url( admin_url( 'admin-post.php' ) ),
			wp_nonce_field( self::ACTION, self::NONCE_NAME, true, false ),
			sprintf( '<input type="hidden" name="action" value="%s" />', self::ACTION ),
			$select
		);

		$form .= '
		<style type="text/css">
			#tribe-section-switcher {
				width: 100%;
				padding: 5px 20px;
				box-sizing: border-box;
				background-color: #fff;
				border: 1px solid #ddd;
				border-top: none;
				box-shadow: 0 1px 1px 0 rgba(0,0,0,0.1);
			}
		</style>';

		return $form;
	}

	/**
	 * Print admin styles using the currently selected section's color
	 *
	 * @return void
	 * @action admin_print_styles
	 */
	public function print_styles() {
		$current = get_user_option( self::FIELD_NAME, get_current_user_id() );
		if ( empty( $current ) ) {
			return;
		}
		$section = Section::factory( $current );
		$color = $section->get_meta( Section_Properties::COLOR );
		if ( empty( $color ) ) {
			return;
		}
		?>
		<style type="text/css">
			form#tribe-section-switcher {
				background-color: <?php echo $color; ?>;
			}
			#adminmenu li.menu-top.menu-tribe-content {
				border-left: 5px solid <?php echo $color; ?>;
				box-sizing: border-box;
			}
			#adminmenu li.menu-top.menu-tribe-content ul.wp-submenu {
				margin-left: -5px;
			}
			#adminmenu li.menu-top.menu-tribe-content.wp-has-current-submenu ul.wp-submenu {
				border-left: 5px solid <?php echo $color; ?>;
				box-sizing: border-box;
			}
		</style>
		<?php
	}

	/**
	 * @param array $menu
	 *
	 * @return array
	 * @filter add_menu_classes
	 */
	public function add_menu_classes( $menu ) {
		$slugs_to_match = [
			'upload.php',
			'nav-menus.php',
			'themes.php'
		];
		foreach ( $menu as &$item ) {
			$slug = $item[2];
			if ( strpos( $slug, 'edit.php' ) !== 0 && ! in_array( $slug, $slugs_to_match ) ) {
				continue;
			}
			if ( strpos( $item[4], 'menu-top' ) === false ) {
				continue;
			}
			$item[4] .= ' menu-tribe-content';
		}
		return $menu;
	}

	/**
	 * If the user doesn't have a current section set, set one
	 *
	 * @return void
	 * @action admin_init
	 */
	public function set_default_section_on_login() {
		$current_user = wp_get_current_user();
		$current_section = get_user_option( self::FIELD_NAME, $current_user->ID );
		if ( ! empty( $current_section ) ) {
			return;
		}
		$default = (int) get_option( Default_Section::TERM_ID, 0 );
		$sections     = $this->get_section_list( $current_user );
		if ( empty( $sections ) ) {
			return; // nothing we can do
		}
		$found_default = array_filter( $sections, function( $term ) use ( $default ) {
			return $term->term_id == $default;
		});
		if ( ! empty( $found_default ) ) {
			update_user_option( $current_user->ID, self::FIELD_NAME, $default );
			return;
		}
		$first = reset( $sections );
		update_user_option( $current_user->ID, self::FIELD_NAME, $first->term_id );
	}
}