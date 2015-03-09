<?php

/**
 * Class Tribe_AdminUI
 *
 * Customize & secure the WordPress admin UI
 */
class Tribe_AdminUI {

	/** @var Tribe_AdminUI */
	private static $instance;

	/**
	 * Set up the hooks
	 */
	private function add_hooks() {
		
		// Remove automatic updates & nags for WordPress, Themes, & Plugins
		//add_action( 'admin_init', array( $this, 'disable_plugin_update_check' ), 100, 0 );
		//remove_action( 'admin_init', '_maybe_update_core' );
		//remove_action( 'admin_init', '_maybe_update_plugins' );
		//remove_action( 'admin_init', '_maybe_update_themes' );
		//add_action( 'admin_head', array( $this, 'remove_nags' ) );

		// Manage user capabilities
		//add_filter( 'map_meta_cap', array( $this, 'filter_admin_capabilities' ), 10, 4 );

		// Admin Bar Clean Up
		//add_action( 'wp_before_admin_bar_render', array( $this, 'remove_admin_bar_links' ) );

		// Admin Clean Up
		//add_action( 'admin_menu', array( $this, 'filter_menus' ), 1000 );
		//add_filter( 'custom_menu_order', array( $this, 'filter_menu_order_activate' ) );
		//add_filter( 'menu_order', array( $this, 'filter_menu_order' ) );

		// Media UI Helpers
		//add_filter( 'admin_post_thumbnail_html', array( $this, 'add_featured_image_instructions' ) );

		// CPT & MU Admin Columns
		//add_filter( 'manage_pages_columns', array( $this, 'remove_admin_comment_column' ) );
		if( function_exists( 'wpseo_auto_load' ) ) {
		//	add_filter( 'manage_edit-post_columns', array( $this, 'remove_admin_seo_columns' ) );
		//	add_filter( 'manage_edit-page_columns', array( $this, 'remove_admin_seo_columns' ) );
		}
		add_filter( 'wpmu_users_columns', array( $this, 'filter_network_users_table_columns' ), 10, 1 );

		// CPT Clean Up
		//add_action( 'add_meta_boxes', array( $this, 'remove_metaboxes' ) );

		// CPT Visual Editor
		//add_filter( 'tiny_mce_before_init', array( $this, 'customize_formatTinyMCE' ) );

		// Set WordPress SEO plugin as last metabox
		if( function_exists( 'wpseo_auto_load' ) ) {
			add_filter( 'wpseo_metabox_prio', function () {
				return 'low';
			} );
		}

	}

	public function disable_plugin_update_check() {
		remove_action( 'load-plugins.php', 'wp_update_plugins' );
		remove_action( 'load-update.php', 'wp_update_plugins' );
		remove_action( 'load-update-core.php', 'wp_update_plugins' );
		remove_action( 'admin_notices', 'woothemes_updater_notice' );
		add_filter( 'pre_site_transient_update_plugins', '__return_null' );
	}

	public function remove_nags() {
		if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
			remove_action( 'admin_notices', 'maintenance_nag' );
			remove_action( 'admin_notices', 'update_nag', 3 );
			remove_action( 'admin_notices', 'tribe_show_fail_message' );
		}
	}

	public function filter_admin_capabilities( $caps, $cap, $user_id, $args ) {
		if ( in_array( $cap, array( 'update_themes', 'update_plugins', 'update_core' ) ) ) {
			return array( 'do_not_allow' );
		}
		if ( ! is_super_admin() && in_array( $cap, array( 'switch_themes', 'install_themes', 'install_plugins', 'edit_themes', 'edit_plugins' ) ) ) {
			return array( 'do_not_allow' );
		}

		return $caps;
	}

	/**
 	 * Remove various admin bar links
 	 */
	public function remove_admin_bar_links() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
		$wp_admin_bar->remove_menu( 'search' );
		$wp_admin_bar->remove_node( 'new-post' );
		$wp_admin_bar->remove_menu( 'new-media' );
		$wp_admin_bar->remove_menu( 'new-user' );
		$wp_admin_bar->remove_node( 'view-site' );
		$wp_admin_bar->remove_menu( 'dashboard' );
		$wp_admin_bar->remove_menu( 'customize' );
		$wp_admin_bar->remove_menu( 'themes' );
		$wp_admin_bar->remove_menu( 'widgets' );
		$wp_admin_bar->remove_menu( 'menus' );
	}

	/**
 	 * Remove unneeded menu items
 	 */
	public function filter_menus() {
		//remove_menu_page( 'edit.php?post_type=acf-field-group' );
		if ( ! is_super_admin() ) {
			remove_menu_page( 'plugins.php' );
			remove_submenu_page( 'themes.php', 'themes.php' );
			remove_menu_page( 'tools.php' );
		}
	}

	public function filter_menu_order_activate() {
		return true;
	}

	/**
 	 * Order admin menu items
 	 */
	public function filter_menu_order() {
		return array(
			'index.php',
			'edit.php?post_type=page',
			'edit.php',
			'upload.php',
			'edit-comments.php'
    	);
	}

	/**
	 * Featured Image Helpers (Image Dimensions, etc)
	 *
	 * @param  string  $content
	 * @param  WP_Post $post
	 * @return string
	 */
	public function add_featured_image_instructions( $content ) {

	    global $post;
	    if( isset( $post ) ) {
	        switch( $post->post_type ) {

	            case 'page':
	            case 'post':
	                $content .= '<hr><p><em>Recommended dimensions are: XXpx X XXpx</em></p>';
	                break;
	        
	        }
	    }
	    return $content;

	}

	/**
 	 * Remove various CPT comment admin column
 	 */
 	public function remove_admin_comment_column( $columns ) {
  		unset( $columns['comments'] );
  		return $columns;
	}

	/**
 	 * Remove various CPT admin columns (Yoast)
 	 */
 	public function remove_admin_seo_columns( $columns ) {
		unset( $columns['wpseo-title'] );
		unset( $columns['wpseo-metadesc'] );
		unset( $columns['wpseo-focuskw'] );
  		return $columns;
	}

	public function filter_network_users_table_columns( $columns ) {
		if ( isset( $columns['blogs'] ) ) {
			unset( $columns['blogs'] );
		}
		return $columns;
	}

	/**
 	 * Remove unneeded CPT meta
 	 */
	public function remove_metaboxes() {
		// Post
		remove_meta_box( 'postcustom', 'post', 'normal' );
		//remove_meta_box( 'trackbacksdiv', 'post', 'normal' );
		remove_meta_box( 'authordiv', 'post', 'normal' );
		remove_meta_box( 'slugdiv', 'post', 'normal' );
		//remove_meta_box( 'commentstatusdiv', 'post', 'normal' );
		//remove_meta_box( 'commentsdiv', 'post', 'normal' );
		// Page
		remove_meta_box( 'postcustom', 'page', 'normal' );
		remove_meta_box( 'slugdiv', 'page', 'normal' );
		remove_meta_box( 'authordiv', 'page', 'normal' );
		remove_meta_box( 'commentstatusdiv', 'page', 'normal' );
		remove_meta_box( 'commentsdiv', 'page', 'normal' );
		// Media
		remove_meta_box( 'authordiv', 'attachment', 'normal' );
		remove_meta_box( 'slugdiv', 'attachment', 'normal' );
		remove_meta_box( 'commentstatusdiv', 'attachment', 'normal' );
		remove_meta_box( 'commentsdiv', 'attachment', 'normal' );
	}

	/**
 	 * TinyMCE customizations, simplify things a bit
 	 *
 	 * @link http://codex.wordpress.org/TinyMCE
 	 */
	public function customize_formatTinyMCE( $settings ) {
		global $current_screen;
		switch ( $current_screen->post_type ) {
			case 'page':
			case 'post':
      			$settings['block_formats'] = 'Paragraph=p; Address=address; Pre=pre; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6';
      			$settings['toolbar1']      = 'bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv';
      			$settings['toolbar2']      = 'formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help';
      			break;
		}
		return $settings;
	}

	/********** Singleton *************/

	/**
	 * Create the instance of the class
	 *
	 * @static
	 * @return void
	 */
	public static function init() {
		self::$instance = self::get_instance();
		self::$instance->add_hooks();
	}

	/**
	 * Get (and instantiate, if necessary) the instance of the class
	 * @static
	 * @return Tribe_AdminUI
	 */
	public static function get_instance() {
		if ( ! is_a( self::$instance, __CLASS__ ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	final public function __clone() {
		trigger_error( "Singleton. No cloning allowed!", E_USER_ERROR );
	}

	final public function __wakeup() {
		trigger_error( "Singleton. No serialization allowed!", E_USER_ERROR );
	}

	protected function __construct() {}
}
