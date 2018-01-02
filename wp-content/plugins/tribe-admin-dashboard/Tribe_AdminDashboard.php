<?php

/**
 * Override the admin dashboard with a totally custom single screen admin.
 */
class Tribe_AdminDashboard {

	/**
	 * Set up the hooks
	 */
	private function add_hooks() {
		//add_action( 'do_meta_boxes', array( $this, 'hide_dashboard_widgets' ), 10, 3 );
		//add_filter( 'get_user_option_screen_layout_dashboard', array( $this, 'force_one_col' ) );
		//add_filter( 'get_user_metadata', array( $this, 'always_show_welcome' ), 10, 3 );
		//add_action( 'welcome_panel', array( $this, 'dashboard_panel' ) );
	}

	/**
	 * Hide Dashboard Widgets
	 *
	 * @param $screen
	 * @param $location
	 * @param $post
	 */
	public function hide_dashboard_widgets( $screen, $location, $post ) {
		remove_action( 'welcome_panel', 'wp_welcome_panel' );
		if ( $screen == 'dashboard' ) {
			//add_screen_option('layout_columns', array('max' => 1 ))
			$remove = array(
				'dashboard_primary',
				'dashboard_secondary',
				'dashboard_recent_comments',
				'dashboard_incoming_links',
				'dashboard_quick_press',
				'dashboard_recent_drafts',
				//'dashboard_right_now',
				//'dashboard_activity',
				'tribe_dashboard_widget',
				'rg_forms_dashboard',
			);
			foreach ( $remove as $slug ) {
				remove_meta_box( $slug, $screen, $location );
			}
		}
	}

	/**
	 * Filter user meta to always show the welcome message.
	 *
	 * @param null|string $meta_value
	 * @param int         $user_id
	 * @param string      $meta_key
	 *
	 * @return bool|null|mixed
	 */
	public function always_show_welcome( $meta_value = null, $user_id = 0, $meta_key ) {
		if ( $meta_key == 'show_welcome_panel' ) {
			$meta_value = true;
		}

		return $meta_value;
	}

	/**
	 * Force dashboard UI to be 1 column
	 *
	 * @return int 1
	 */
	public function force_one_col() {
		return 1;
	}

	/**
	 * Queue the Tribe Dashboard Widget
	 */
	public function dashboard() {
		wp_add_dashboard_widget( 'dashboard_tribe', __( 'Welcome & Help Dashboard' ), array( $this, 'dashboard_panel' ) );
	}

	/**
	 * Displays the Tribe dashboard panel
	 *
	 * @todo handle styling externally
	 * @todo dynamically manage welcome content per site, globally, and possibly per user role.
	 */
	public function dashboard_panel() {
		?>
		
		<style>
			.welcome-panel-close {
				display : none;
			}

			#welcome-panel.hidden {
				display : block;
			}

			.welcome-panel-column-container {
				border-top: 1px solid #eee;
				margin-top: 26px;
			}

			.welcome-panel-column-container ~ .welcome-panel-column-container {
				margin-top: 14px;
			}

			.welcome-panel .welcome-panel-column.welcome-panel-grid-50 {
				width: 48%;
			}

			.welcome-panel-column h4 {
				margin-top: 6px;
				font-size: 14px;
			}

			.welcome-panel-column p {
				padding-right : 10px;
				line-height   : 1.5em;
			}

			/*
			#screen-options-link-wrap {
				display : none;
			}
			*/

			#screen-options-wrap label[for="wp_welcome_panel-hide"] {
				display: none;
			}

			.welcome-panel-clear {
				clear: both;
			}

			.client-logo img {
				max-width  : 400px;
				max-height : 125px;
				display    : block;
				float      : left;
				margin     : 0 25px 10px 0;
			}
		</style>

		<?php /*

			Client Dashboard

			This is to serve as a base welcome and help dashboard for clients, which combines 
			several approaches that have been used across different client projects. Feel free
			to customize this and change as needed for the specifics of your project.

			NOTE: We make use of the WordPress font icon, Dashicons, which can be found
			here: http://melchoyce.github.io/dashicons/

		*/ ?>

		<div class="welcome-panel-content">

			<?php // BEGIN: Branding & Welcome ?>

			<?php if ( function_exists( 'tribe_get_logo' ) && tribe_get_logo() ) { ?>
				<div class="client-logo">
					<a href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo( 'name' ); ?>">
						<img class="logo" src="<?php echo tribe_get_logo(); ?>" alt="<?php the_title(); ?>" />
					</a>
				</div>
			<?php } ?>

			<h3><?php bloginfo( 'name' ); ?></h3>
			<p class="about-description"><?php _e( 'Welcome!' ); ?></p>
			<p><?php _e( 'This is the dashboard for your site, and will be your base of operations. Below you 
				will find resources to help you learn how to manage the site, as well as various shortcuts to 
				help you manage the functionality and components found across the site.' ); ?></p>

			<?php // END: Branding & Welcome ?>

			<?php // BEGIN: Shortcuts ?>
		
			<div class="welcome-panel-column-container">

				<h4><?php _e( 'Shortcuts' ); ?></h4>
				<p><?php _e( 'These are shortcuts to help you save time as you manage the site.' ); ?></p>

				<?php /*
				<div class="welcome-panel-column">
					<h4><?php _e( 'Get Started' ); ?></h4>
					<a class="button button-primary button-hero load-customize hide-if-no-customize" href="<?php echo wp_customize_url(); ?>">
						<?php _e( 'Customize Your Site' ); ?>
					</a>
				</div><!-- .welcome-panel-column -->
				*/ ?>

				<div class="welcome-panel-column welcome-panel-grid-50">
					<h4><?php _e( 'Next Steps' ); ?></h4>
					<ul>
						<li><?php printf( '<a href="%s" class="welcome-icon welcome-edit-page">' . __( 'Edit the home page and other options' ) . '</a>', admin_url( 'admin.php?page=acf-options' ) ); ?></li>
						<li><?php printf( '<a href="%s" class="welcome-icon welcome-edit-page">' . __( 'Manage films' ) . '</a>', admin_url( 'edit.php?post_type=film' ) ); ?></li>
						<li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Edit site pages' ) . '</a>', admin_url( 'edit.php?post_type=page' ) ); ?></li>
						<?php /* <li><?php printf( '<a href="%s" class="welcome-icon welcome-add-page">' . __( 'Add a post' ) . '</a>', admin_url( 'post-new.php' ) ); ?></li> */ ?>
						<?php /* <li><?php printf( '<div class="welcome-icon welcome-widgets-menus">' . __( 'Manage post <a href="%1$s">comments</a>' ) . '</div>', admin_url( 'edit-comments.php' ) ); ?></li> */ ?>
						<li><?php printf( '<a href="%s" class="welcome-icon welcome-view-site">' . __( 'View the site' ) . '</a>', home_url( '/' ) ); ?></li>
					</ul>
				</div>

				<div class="welcome-panel-column welcome-panel-last welcome-panel-grid-50">
					<h4><?php _e( 'More Actions' ); ?></h4>
					<ul>
						<li><?php printf( '<div class="welcome-icon welcome-widgets-menus">' . __( 'Manage site' ) . ' <a href="%1$s">'. __( 'menus' ) .'</a></div>', admin_url( 'nav-menus.php' ) ); ?></li>
						<li><?php printf( '<div class="welcome-icon dashicons-admin-network">' . __( 'Manage' ) . ' <a href="%1$s">'. __( 'subscriptions & memberships' ) .'</a></div>', admin_url( 'admin.php?page=rcp-members' ) ); ?></li>
						<li><?php printf( '<div class="welcome-icon dashicons-chart-bar">' . __( 'Manage site' ) . ' <a href="%1$s">'. __( 'SEO' ) .'</a></div>', admin_url( 'admin.php?page=wpseo_dashboard' ) ); ?></li>
					</ul>
				</div>

				<p class="welcome-panel-clear"><?php _e( 'Below you will find various resources and tutorials to help you learn how to manage your site.' ); ?></p>

			</div><!-- .welcome-panel-column-container -->

			<?php // END: Shortcuts ?>

			<?php // BEGIN: WordPress Basics & Site Tutorials ?>
		
			<div class="welcome-panel-column-container">

				<h4><?php _e( 'WordPress Resources & Site Tutorials' ); ?></h4>
				<p><?php _e( 'These are resources for newcomers to WordPress, which cover broader use of the platform.' ); ?></p>

				<div class="welcome-panel-column welcome-panel-grid-50">
					<h4><?php _e( 'WordPress Basics' ); ?></h4>
					<p><?php _e( 'These are resources for newcomers to WordPress, which cover broader use of the platform.' ); ?></p>
					<ul>
						<li><?php printf( '<a href="%s" class="welcome-icon welcome-learn-more" target="_blank">' . __( 'Learn the basics of WordPress' ) . '</a>', __( 'http://codex.wordpress.org/First_Steps_With_WordPress' ) ); ?></li>
					</ul>
				</div><!-- .welcome-panel-column -->

				<div class="welcome-panel-column welcome-panel-last welcome-panel-grid-50">
					<h4><?php _e( 'Tutorials' ); ?></h4>
					<p><?php _e( 'The videos in this section go over the more complex components and functionality of the site.' ); ?></p>
					<ul>
						<li><?php printf( '<a href="%s" class="welcome-icon welcome-learn-more" target="_blank">' . __( 'Managing users' ) . '</a>', 'http://vimeo.com/95038654' ); ?></li>
						<li><?php printf( '<a href="%s" class="welcome-icon welcome-learn-more" target="_blank">' . __( 'Managing film content' ) . '</a>', 'http://vimeo.com/95037566' ); ?></li>
					</ul>
				</div><!-- .welcome-panel-column -->

				<p class="welcome-panel-clear"><?php printf( __( 'NOTE: The password to view the tutorial videos is:' ) . ' <strong style="color: #444;">"' . __( 'tribe_helpers' ) . '"</strong>' ); ?></p>
			
			</div><!-- .welcome-panel-column-container -->

			<?php // END: WordPress Basics & Site Tutorials ?>

			<p><em><?php _e( 'A Modern Tribe Production' ); ?></em></p>

		</div><!-- .welcome-panel-content -->
		
	<?php
	}

	/********** Singleton *************/

	/** @var Tribe_AdminDashboard */
	private static $instance;

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
	 * @return Tribe_AdminDashboard
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
