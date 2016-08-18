<?php
/*
Plugin Name: Domain Mapper Remote Login Wrapper
Description: Make sure that logins always happen on the correct domain, using the correct scheme
Author: Modern Tribe, Inc.
Version: 1.0
Author URI: http://tri.be
*/

if ( !class_exists('DM_RemoteLoginWrapper') ) {
	class DM_RemoteLoginWrapper {
		private static $instance = NULL;
		public static function init() {
			if ( function_exists( 'remote_login_js_loader' ) ) {
				if ( empty(self::$instance) ) {
					self::$instance = new self();
				}
			}
		}
		private function __construct() {
			$this->set_hooks();
			$this->filter_admin_ssl_requirement();
		}

		private function set_hooks() {
			remove_action( 'wp_head', 'remote_login_js_loader' );
			if ( get_current_user_id() ) {
				remove_action( 'template_redirect', 'redirect_to_mapped_domain' );
			}

			remove_action( 'login_head', 'redirect_login_to_orig' );
			add_action( 'login_init', array( $this, 'redirect_to_unmapped_login_page' ), 10, 0 );
			add_filter( 'site_url', array( $this, 'filter_login_form_url_scheme' ), 10, 4 );
		}

		private function filter_admin_ssl_requirement() {
			$path = basename(parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ));
			if ( $path == 'wp-login.php' && isset($_GET['action']) && $_GET['action'] == 'postpass' ) {
				force_ssl_admin(FALSE);
			}
		}

		public function redirect_to_unmapped_login_page() {
			
			if ( !get_site_option( 'dm_remote_login' ) || ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'logout' ) || isset( $_GET[ 'loggedout' ] ) || !empty($_REQUEST['post_password']) ) {
				return;
			}
			
			$url = get_original_url( 'siteurl' );
			if ( $url != site_url() ) {
				$url .= "/wp-login.php";
				if ( !empty($_GET['redirect_to']) ) {
					$redirect = str_replace( site_url(), get_original_url('siteurl'), $_GET['redirect_to'] );
					$url = add_query_arg( 'redirect_to', urlencode($redirect), $url );
				}
				wp_redirect( $url, 303 );
				exit();
			}
		}

		public function filter_login_form_url_scheme( $url, $path, $scheme, $blog_id ) {
			if ( $scheme == 'login_post' && $path == 'wp-login.php?action=postpass' ) {
				$home_url = home_url();
				if ( strpos( $home_url, 'https' ) !== 0 ) {
					$url = str_replace( 'https://', 'http://', $url );
				}
			}
			return $url;
		}

	}

	add_action( 'plugins_loaded', array( 'DM_RemoteLoginWrapper', 'init' ), 0, 0 );
}