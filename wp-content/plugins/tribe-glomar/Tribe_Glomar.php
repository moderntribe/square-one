<?php declare(strict_types=1);

namespace Tribe\Plugin;

/**
 * Force the frontend of the site to hide if you are not logged in.
 *
 * @see http://www.radiolab.org/story/confirm-nor-deny/
 */
class Tribe_Glomar {

	/**
	 * Name of the cookie.
	 *
	 * @var string
	 */
	public const COOKIE = 'wordpress_logged_in_glomar';

	/**
	 * Path for the frontend redirect.
	 *
	 * @var string
	 */
	private $path = 'glomar';

	/**
	 * Determines if the current site is public or not.
	 *
	 * @var bool
	 */
	private $is_public = false;

	/**
	 * Holds instance of the admin class.
	 *
	 * @var \Tribe\Plugin\Tribe_Glomar_Admin|null
	 */
	private ?Tribe_Glomar_Admin $admin = null;

	/**
	 * Minimum capability used to determine if a user has 'admin access',
	 * as user_can_access_admin_page() is unavailable outwith the admin
	 * environment.
	 *
	 * @var string
	 */
	protected string $min_admin_cap = 'edit_posts';

	/**
	 * Class constructor.
	 *
	 * @param Tribe_Glomar_Admin Instance of the admin class.
	 */
	private function __construct( Tribe_Glomar_Admin $admin ) {
		$this->admin = new Tribe_Glomar_Admin();
	}

	/**
	 * Hooks to register with WP Lifecycle.
	 *
	 * @return void
	 */
	protected function add_hooks(): void {

		if ( defined( 'TRIBE_GLOMAR_PUBLIC' ) && TRIBE_GLOMAR_PUBLIC === true ) {
			$this->is_public = true;
			add_filter( 'robots_txt', [ $this, 'rewrite_robots_txt' ], 999 );
		} else {
			add_filter( 'option_blog_public', [ $this, 'filter_public_option' ] );
			add_filter( 'default_option_blog_public', [ $this, 'filter_public_option' ] );
		}

		$this->path = apply_filters( 'glomar_path', $this->path );
		add_action( 'init', [ $this, 'add_endpoint' ], 10, 0 );
		add_action( 'parse_request', [ $this, 'handle_request' ], 10, 1 );
		add_filter( 'template_redirect', [ $this, 'intercept_pageload' ] );
		add_filter( 'http_request_reject_unsafe_urls', '__return_false' );
		add_action( 'wp_logout', [ $this, 'clear_cookie_on_logout' ], 10, 0 );

		add_action( 'tribe_glomar_current_action_glomar', [ $this, 'handle_glomar_page' ] );
		add_action( 'tribe_glomar_current_action_login', [ $this, 'handle_redirect_login' ] );

		if ( ! is_admin() ) {
			return;
		}

		$this->admin->add_hooks();
	}

	/**
	 * Rewrite robots_txt to avoid sitemaps etc.
	 *
	 * @param string $output
	 *
	 * @return string
	 */
	public function rewrite_robots_txt( $output ): string {
		$output  = "User-agent: *\n";
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
	public function filter_public_option( $option ): int {
		return ( $option == 2 ) ? 2 : 0;
	}

	public function add_endpoint() {
		add_rewrite_endpoint( $this->path, EP_ROOT );
	}

	/**
	 * @param \WP $wp
	 *
	 * @return void
	 */
	public function handle_request( \WP $wp ) {
		if ( ! isset( $wp->query_vars['glomar'] ) ) {
			return;
		}

		$action = $this->admin->get_action();

		do_action( 'tribe_glomar_current_action', $action );
		do_action( 'tribe_glomar_current_action_' . $action );
	}

	/**
	 * Handle the "Redirect to login" action
	 */
	public function handle_redirect_login() {
		wp_redirect( wp_login_url() );
		exit;
	}

	/**
	 * Handle the "Load Glomar Template" action
	 */
	public function handle_glomar_page() {

		$blocker = apply_filters( 'glomar_page', get_template_directory() . '/glomar.php' );

		$response_code = ( $this->is_public ) ? 200 : 404;

		if ( ! file_exists( $blocker ) ) {
			$this->handle_glomar_page_default( $response_code );
			exit;
		}

		status_header( $response_code );
		include $blocker;
		exit;
	}

	/**
	 * Show a default glomar message if there's no glomar template on the theme.
	 *
	 * @param int $response_code
	 */
	protected function handle_glomar_page_default( $response_code ) {
		add_filter( 'nocache_headers', '__return_empty_array' ); // you can cache this page

		wp_die(
			wp_kses_post( $this->admin->get_message() ),
			__( 'You\'ve been Glomar\'d', 'tribe' ),
			[ 'response' => $response_code ]
		);
	}

	/**
	 * Intercept the page loads with the Glomar 404.
	 */
	public function intercept_pageload() {
		// Skip redirect if we're already at the glomar page.
		if ( isset( $_SERVER['REQUEST_URI'] ) && home_url( str_replace( '/wp', '', $_SERVER['REQUEST_URI'] ) ) === home_url( $this->path ) ) {
			return;
		}

		// Allow access to logged in users, whitelisted users, and requests with a secret key.
		if ( $this->bypass() ) {
			if ( apply_filters( 'glomar_disable_page_cache', true ) ) {
				do_action( 'do_not_cache' );
			}

			// Set a cookie so page caching knows to let us in.
			setcookie( self::COOKIE, '1', time() + ( MINUTE_IN_SECONDS * 10 ), COOKIEPATH, (string) COOKIE_DOMAIN );

			return;
		}

		if ( is_robots() ) {
			return;
		}

		/*
		 * usually, this runs at priority 1000. We don't want to move it from there for logged-in
		 * users, but it still needs to run before we do our error page
		 */
		wp_redirect_admin_locations();

		do_action( 'do_not_cache' );

		wp_safe_redirect( home_url( $this->path ), 303 );
		exit;
	}

	/**
	 * Determine if we should bypass Glomar
	 *
	 * @return bool
	 */
	private function bypass(): bool {

		if ( is_user_logged_in() ) {
			if ( current_user_can( $this->min_admin_cap ) ) {
				return true; // assumes that we've made this a splash page and lower level users should not bypass it.
			}

			if ( ! $this->is_public ) {
				return true; // this is so we can test lower level user accounts on a glomar site
			}
		}

		if ( $this->is_ip_whitelisted() ) {
			return true;
		}

		if ( $this->has_a_secret_key() ) {
			return true;
		}

		if ( apply_filters( 'bypass_glomar', false ) ) {
			return true;
		}

		return false;
	}

	/**
	 * See if we've passed a secret key
	 *
	 * @return bool
	 */
	private function has_a_secret_key() {
		$secret = $this->admin->get_secret();

		if ( ! empty( $_COOKIE[ $secret ] ) ) {
			return true;
		}

		if ( ! empty( $_GET[ $secret ] ) ) {
			setcookie( $secret, '1', time() + ( DAY_IN_SECONDS * 30 ), COOKIEPATH, (string) COOKIE_DOMAIN );

			return true;
		}

		return false;
	}

	/**
	 * See if the user's IP is in the whitelist.
	 *
	 * @return bool
	 */
	private function is_ip_whitelisted() {
		return in_array( $_SERVER['REMOTE_ADDR'], $this->admin->allowed_ip_addresses() );
	}

	/**
	 * Clear the cookie when the user logs out.
	 */
	public function clear_cookie_on_logout() {
		if ( $this->is_ip_whitelisted() ) {
			return;
		}

		setcookie( self::COOKIE, '1', time() - YEAR_IN_SECONDS, COOKIEPATH, (string) COOKIE_DOMAIN );
	}

	/**
	 * Instance of this class for use as singleton
	 */
	private static $instance;

	/**
	 * Create the instance of the class
	 *
	 * @static
	 *
	 * @return void
	 */
	public static function init( Tribe_Glomar_Admin $admin ) {
		self::$instance = self::get_instance( $admin );
		self::$instance->add_hooks();
	}

	/**
	 * Get (and instantiate, if necessary) the instance of the class
	 *
	 * @static
	 *
	 * @return \Tribe\Plugin\Tribe_Glomar
	 */
	public static function get_instance( Tribe_Glomar_Admin $admin ) {
		if ( ! is_a( self::$instance, __CLASS__ ) ) {
			self::$instance = new self( $admin );
		}

		return self::$instance;
	}

}
