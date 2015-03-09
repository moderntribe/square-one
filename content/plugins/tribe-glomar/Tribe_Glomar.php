<?php

/**
 * Force the frontend of the site to hide if you are not logged in.
 *
 * @see http://www.radiolab.org/story/confirm-nor-deny/
 */
class Tribe_Glomar {

	const COOKIE = 'tribe_glomar';

	private $is_public = FALSE;

	/**
	 * Minimum capability used to determine if a user has 'admin access',
	 * as user_can_access_admin_page() is unavailable outwith the admin
	 * environment.
	 *
	 * @var string
	 */
	protected $min_admin_cap = 'edit_posts';


	public function __construct() {}

	protected function add_hooks() {

		if ( defined( 'TRIBE_GLOMAR_PUBLIC' ) && TRIBE_GLOMAR_PUBLIC === true ) {
			$this->is_public = TRUE;
			add_filter( 'robots_txt', array( $this, 'rewrite_robots_txt' ), 999 );
		} else {
			add_filter( 'option_blog_public', array( $this, 'filter_public_option' ) );
			add_filter( 'default_option_blog_public', array( $this, 'filter_public_option' ) );
		}

		add_filter( 'template_redirect', array( $this, 'intercept_pageload' ) );
		add_filter( 'http_request_reject_unsafe_urls', '__return_false' );
		add_action( 'wp_logout', array( $this, 'clear_cookie_on_logout' ), 10, 0 );
		if ( is_admin() ) {
			$admin = new Tribe_Glomar_Admin();
			$admin->add_hooks();
		}
	}

	/**
	 * Rewrite robots_txt to avoid sitemaps etc.
	 *
	 * @param string $output
	 * @return string
	 */
	public function rewrite_robots_txt( $output ) {
		$output = "User-agent: *\n";
		$output .= "Disallow: /wp-admin/\n";
		$output .= "Disallow: /wp-includes/\n";
		return $output;
	}

	/**
	 * Make sure that the site is forced to be private if glomar is on.
	 *
	 * @param $option
	 *
	 * @return int
	 */
	public function filter_public_option( $option ) {
		return ( $option == 2 ) ? 2 : 0;
	}

	/**
	 * Intercept the page loads with the Glomar 404.
	 */
	public function intercept_pageload() {

		// Allow access to logged in users, whitelisted users, and requests with a secret key.
		if ( $this->bypass() ) {
			// set a cookie so page caching knows to let us in
			setcookie( self::COOKIE, '1', time() + ( MINUTE_IN_SECONDS * 10 ), COOKIEPATH, COOKIE_DOMAIN );
			return;
		}

		if ( is_robots() )
			return;

		/*
		 * usually, this runs at priority 1000. We don't want to move it from there for logged-in
		 * users, but it still needs to run before we do our error page
		 */
		wp_redirect_admin_locations();

		do_action('do_not_cache');

		$blocker = apply_filters( 'glomar_page', get_template_directory() . '/glomar.php' );
		$response_code = ( $this->is_public ) ? 200 : 404;
		if ( file_exists( $blocker ) ) {
			status_header($response_code);
			include( $blocker );
			exit();
		} else {
			wp_die(
				'<p><a href="http://www.radiolab.org/story/confirm-nor-deny/"><img width="100%" src="http://www.wnyc.org/i/620/372/l/80/1/Glomar.jpg"></a></p>' .
				'<h1>'.__( "You've been Glomar'd", 'tribe' ).'</h1>'.
				'<p>'.__( 'We can neither confirm nor deny the existence or nonexistence of records responsive to your request. The fact of the existence or nonexistence of requested records is currently and properly classified and is intelligence sources and methods information that is protected from disclosure.', 'tribe' ) . '</p>',
				__( "You've been Glomar'd", 'tribe' ),
				array( 'response' => $response_code )
			);
		}
		exit();
	}

	/**
	 * Determine if we should bypass Glomar
	 *
	 * @return bool
	 */
	private function bypass() {

		if ( is_user_logged_in() ) {

			if ( current_user_can( $this->min_admin_cap ) )
				return true; // assumes that we've made this a splash page and lower level users should not bypass it.

			if ( !$this->is_public )
				return true; // this is so we can test lower level user accounts on a glomar site

		}

		if ( $this->is_ip_whitelisted() )
			return true;

		if ( $this->has_a_secret_key() )
			return true;

		if ( apply_filters( 'bypass_glomar', false ) )
			return true;

		return false;
	}

	/**
	 * See if we've passed a secret key
	 *
	 * @return bool
	 */
	private function has_a_secret_key() {
		$admin  = new Tribe_Glomar_Admin();
		$secret = $admin->get_secret();

		if( !empty( $_COOKIE[ $secret ] ) ){
			return true;
		}

		if( !empty( $_GET[ $secret ] ) ){
			setcookie( $secret, '1', time() + ( DAY_IN_SECONDS * 30 ), COOKIEPATH, COOKIE_DOMAIN );
			return true;
		} else {
			return false;
		}


	}

	/**
	 * See if the user's IP is in the whitelist.
	 *
	 * @return bool
	 */
	private function is_ip_whitelisted() {
		$admin = new Tribe_Glomar_Admin();
		return in_array( $_SERVER['REMOTE_ADDR'], $admin->allowed_ip_addresses() );
	}

	/**
	 * Clear the cookie when the user logs out.
	 */
	public function clear_cookie_on_logout() {
		if ( !$this->is_ip_whitelisted() ) {
			setcookie( self::COOKIE, '1', time() - YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		}
	}

	/********** SINGLETON FUNCTIONS **********/

	/* Don't edit below here! */

	/**
	 * Instance of this class for use as singleton
	 */
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
	 *
	 * @static
	 * @return Tribe_Glomar
	 */
	public static function get_instance() {
		if ( ! is_a( self::$instance, __CLASS__ ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}