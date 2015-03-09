<?php

/**
 * Class Tribe_Glomar_Admin
 */
class Tribe_Glomar_Admin {
	const SLUG = 'tribe_glomar_settings';


	public function add_hooks() {
		if ( is_multisite() ) {
			add_action( 'network_admin_menu', array($this, 'register_admin_page'), 10, 0);
			add_action( 'network_admin_edit_'.self::SLUG, array($this, 'save_network_admin_page'), 10, 0);
		} else {
			add_action( 'admin_menu', array( $this, 'register_admin_page' ), 10, 0 );
		}
	}


	public function register_admin_page() {
		add_submenu_page(
			is_multisite()?'settings.php':'options-general.php',
			__('Glomar', 'tribe-glomar'),
			__('Glomar', 'tribe-glomar'),
			is_multisite()?'manage_network':'manage_options',
			self::SLUG,
			array($this, 'display_admin_page')
		);

		add_settings_section(
			'glomar-settings',
			__('Access Settings', 'tribe-glomar'),
			'__return_false',
			self::SLUG
		);

		add_settings_field(
			'glomar-ip-whitelist',
			__('Permitted IP Address', 'tribe-glomar'),
			array($this, 'display_ip_field'),
			self::SLUG,
			'glomar-settings'
		);

		add_settings_field(
			'glomar-secret',
			__('Secret URL Parameter', 'tribe-glomar'),
			array($this, 'display_secret_field'),
			self::SLUG,
			'glomar-settings'
		);

		if ( !is_multisite() ) {
			register_setting(
				self::SLUG,
				'glomar-ip-whitelist',
				array( $this, 'sanitize_ip_list')
			);
			register_setting(
				self::SLUG,
				'glomar-secret',
				array( $this, 'sanitize_secret')
			);
		}
	}

	public function display_admin_page() {
		$title = __('Glomar Settings', 'tribe-glomar');
		if ( is_multisite() ) {
			$action = network_admin_url( 'edit.php?action='.self::SLUG );
		} else {
			$action = admin_url('options.php');
		}
		ob_start();
		echo "<form action='".$action."' method='post'>";
		$this->settings_fields();
		do_settings_sections(self::SLUG);
		submit_button();
		echo "</form>";
		$content = ob_get_clean();
		require_once( 'views/settings-page-wrapper.php' );
	}

	private function settings_fields() {
		if ( is_multisite() ) {
			echo '<input type="hidden" name="action" value="'.self::SLUG.'" />';
			wp_nonce_field( self::SLUG . '-options' );
		} else {
			settings_fields( self::SLUG );
		}
	}

	public function save_network_admin_page() {
		// settings API doesn't work at the network level, so we save it ourselves
		if ( !isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], self::SLUG.'-options') ) {
			return;
		}

		$this->save_ip_field();
		$this->save_secret_field();

		wp_redirect(add_query_arg(array('page' => self::SLUG, 'updated' => 'true'), network_admin_url('settings.php')));
		exit();
	}

	/******** IP Address Field ********/

	public function display_ip_field() {
		$ip = implode("\n", $this->allowed_ip_addresses());
		printf('<textarea name="%s" rows="6" cols="30">%s</textarea>', 'glomar-ip-whitelist', esc_textarea($ip));
		echo '<p class="description">';
		_e('Include one IP address per line. Only logged in users will be able to view the site from other IP addresses.', 'tribe-glomar');
		if ( isset($_SERVER['REMOTE_ADDR']) ) {
			printf( ' '.__('Your current IP address is <code>%s</code>.', 'tribe-glomar'), $_SERVER['REMOTE_ADDR'] );
		}
		echo '</p>';
	}

	private function save_ip_field() {
		if ( isset($_POST['glomar-ip-whitelist']) ) {
			$addresses = $this->sanitize_ip_list($_POST['glomar-ip-whitelist']);
			$this->set_option('glomar-ip-whitelist', $addresses);
		}
	}

	public function sanitize_ip_list( $list ) {
		if ( empty( $list ) ) return array();
		$addresses = explode("\n", $list);
		$save = array();
		foreach ( $addresses as $a ) {
			$a = trim($a);
			if ( preg_match('!^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$!', $a) ) {
				$save[] = $a;
			}
		}
		return $save;
	}

	public function allowed_ip_addresses() {
		return (array)$this->get_option('glomar-ip-whitelist', array('127.0.0.1'));
	}

	/******** Secret Field ********/

	public function display_secret_field() {
		$secret = $this->get_secret();
		printf('<input name="%s" value="%s">', 'glomar-secret', $secret);
		printf('<p class="description">%s</p>', __('Enter an optional secret string that can be added to a url to bypass glomar.', 'tribe-glomar') );
	}

	private function save_secret_field() {
		if ( isset($_POST['glomar-secret']) ) {
			$secret = $this->sanitize_secret( $_POST['glomar-secret'] );
			$this->set_option('glomar-secret', $secret);
		}
	}

	public function sanitize_secret( $secret ) {
		$secret = sanitize_title($secret);
		return $secret;
	}

	public function get_secret() {
		return (string)$this->get_option('glomar-secret');
	}

	/******** Options Management ********/

	private function get_option( $option, $default = FALSE ) {
		if ( is_multisite() ) {
			return get_site_option($option, $default);
		} else {
			return get_option($option, $default);
		}
	}

	private function set_option( $option, $value ) {
		if ( is_multisite() ) {
			update_site_option($option, $value);
		} else {
			update_option($option, $value);
		}
	}
}
