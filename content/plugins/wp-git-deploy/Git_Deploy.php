<?php

class Git_Deploy {
	const SLUG = 'git-deploy';
	const NONCE_ACTION = 'git_deploy_nonce_action';
	const NONCE_NAME = 'git_deploy_nonce_name';

	/** @var Git_Deploy */
	private static $instance;

	private function add_hooks() {
		add_action( 'muplugins_loaded', array( $this, 'check_for_request' ) );
		add_action( 'plugins_loaded', array( $this, 'check_for_request' ) ); // just in case this isn't a muplugin

		if ( is_multisite() ) {
			add_action( 'network_admin_menu', array($this, 'register_network_admin_page'), 10, 0);
			add_action( 'network_admin_edit_'.self::SLUG, array($this, 'save_network_admin_page'), 10, 0);
		} else {
			add_action( 'admin_menu', array( $this, 'register_network_admin_page' ), 10, 0 );
		}
	}

	public function register_network_admin_page() {
		add_submenu_page(
			is_multisite()?'settings.php':'options-general.php',
			__('Git Deployment', 'git-info'),
			__('Deployment', 'git-info'),
			is_multisite()?'manage_network':'manage_options',
			self::SLUG,
			array($this, 'display_network_admin_page')
		);

		add_settings_section(
			'git-deployment-settings',
			__('Deployment Settings', 'git-info'),
			array($this, 'display_settings_section'),
			self::SLUG
		);

		add_settings_field(
			'git-deployment-ip-addresses',
			__('Permitted IP Address', 'git-info'),
			array($this, 'display_ip_field'),
			self::SLUG,
			'git-deployment-settings'
		);

		add_settings_section(
			'git-deploynow',
			__('Deploy Now', 'git-info'),
			array($this, 'display_deploy_now_settings_section'),
			self::SLUG
		);

		if ( !is_multisite() ) {
			register_setting(
				'git-deployment-ip-addresses',
				self::SLUG
			);
		}
	}

	public function display_network_admin_page() {
		$title = __('Git Deployment', 'git-info');
		require_once( 'views/network-admin.php' );
	}

	public function save_network_admin_page() {
		// settings API doesn't work at the network level, so we save it ourselves
		if ( !isset($_POST[self::NONCE_NAME]) || !wp_verify_nonce($_POST[self::NONCE_NAME], self::NONCE_ACTION) ) {
			return;
		}

		$this->save_ip_field();

		wp_redirect(add_query_arg(array('page' => self::SLUG, 'updated' => 'true'), network_admin_url('settings.php')));
		exit();
	}

	public function display_settings_section() {
		// nothing to do
	}

	public function display_deploy_now_settings_section() {
		$url = add_query_arg(self::SLUG, 1, home_url());
		printf(
			'<p>%s</p>',
			sprintf(
				__('Hit the <a href="%s" target="_blank">deployment endpoint</a> manually to force an update to the latest version.', 'git-info'),
				$url
			)
		);
	}

	public function display_ip_field() {
		$ip = implode("\n", $this->allowed_ip_addresses());
		printf('<textarea name="%s" rows="6" cols="30">%s</textarea>', 'git-deployment-ip-addresses', esc_textarea($ip));
		echo '<p class="description">';
		_e('Include one IP address per line. Only logged in users will be able to deploy from other IP addresses.', 'git-info');
		echo '</p>';
	}

	private function save_ip_field() {
		if ( isset($_POST['git-deployment-ip-addresses']) ) {
			$addresses = explode("\n", $_POST['git-deployment-ip-addresses']);
			$save = array();
			foreach ( $addresses as $a ) {
				$a = trim($a);
				if ( preg_match('!^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$!', $a) ) {
					$save[] = $a;
				}
			}
			$this->set_option('git-deployment-ip-addresses', $save);
		}
	}

	private function allowed_ip_addresses() {
		return (array)$this->get_option('git-deployment-ip-addresses', array('127.0.0.1'));
	}

	private function get_option( $option, $default = FALSE ) {
		if ( defined('BLOG_ID_CURRENT_SITE') ) {
			return get_blog_option(BLOG_ID_CURRENT_SITE, $option, $default);
		} else {
			return get_option($option, $default);
		}
	}

	private function set_option( $option, $value ) {
		if ( defined('BLOG_ID_CURRENT_SITE') ) {
			update_blog_option(BLOG_ID_CURRENT_SITE, $option, $value);
		} else {
			update_option($option, $value);
		}
	}

	public function check_for_request() {
		if ( !empty($_GET[self::SLUG]) ) {
			if ( $this->authenticate_remote_deployment() ) {
				$message = $this->do_pull();
				wp_die($message, 'Complete', array('response' => 200));
			} else {
				wp_die("I'm telling mom!");
			}
		}
		// we only need to run this once. If triggered by muplugins_loaded,
		// remove the subsequent trigger. If triggered by plugins_loaded,
		// this doesn't matter
		remove_action( 'plugins_loaded', array( $this, 'check_for_request' ) );
	}

	private function authenticate_remote_deployment() {
		if ( !defined('AUTH_COOKIE') ) {
			// ensure we have the functions/constants we need for authentication
			if ( is_multisite() ) {
				ms_cookie_constants(  );
			}
			wp_cookie_constants( );
			require_once( ABSPATH . WPINC . '/vars.php' );
			require_once( ABSPATH . WPINC . '/pluggable.php' );
		}

		// if a logged-in super admin is hitting the URL with a browser, allow it
		if ( is_super_admin() ) {
			return TRUE;
		}

		// otherwise, restrict to a list of IP addresses
		if ( in_array( $_SERVER['REMOTE_ADDR'], $this->allowed_ip_addresses() ) ) {
			return TRUE;
		}
		return FALSE;
	}

	private function do_pull() {
		$path = ABSPATH;
		$cmd = 'cd '.$path.'; git fetch; git reset --hard $(git rev-parse --symbolic-full-name @{u}); git submodule update --recursive --init;';
		$message = "$ $cmd<br />";
		exec( $cmd, $output, $return );
		$message .= implode('<br />', $output);
		return $message;
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
	}

	/**
	 * Get (and instantiate, if necessary) the instance of the class
	 * @static
	 * @return Git_Deploy
	 */
	public static function get_instance() {
		if ( !is_a( self::$instance, __CLASS__ ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	final public function __clone() {
		trigger_error( "No cloning allowed!", E_USER_ERROR );
	}

	final public function __sleep() {
		trigger_error( "No serialization allowed!", E_USER_ERROR );
	}

	protected function __construct() {
		$this->add_hooks();
	}
}
