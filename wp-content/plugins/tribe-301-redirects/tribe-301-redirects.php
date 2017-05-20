<?php
/*
Plugin Name: Tribe 301 Redirects
Plugin URI: http://tri.be/
Description: Create a list of URLs that you would like to 301 redirect to another page or site
Version: 2
Author: Modern Tribe, Scott Nellé
Author URI: http://www.scottnelle.com/
*/

/*
    Original Plugin: http://www.scottnelle.com/simple-301-redirects-plugin-for-wordpress/

    Copyright 2009  Scott Nellé  (email : theguy@scottnelle.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists("Tribe_301_Redirects")) {
	class Tribe_301_Redirects {
		const OPTION = 'tribe-301-redirects';

		/** @var self */
		private static $instance = NULL;

		/**
		 * Read the list of redirects and if the current page
		 * is found in the list, send the visitor on her way
		 */
		public function do_static_redirect( $wp ) {
			$userrequest = $this->get_normalized_path();

			$redirects = get_option( self::OPTION, array() );
			if ( !empty( $redirects['string'] ) ) {
				if ( isset( $redirects['string'][$userrequest] ) ) {
					$redirect = $this->append_query_string($redirects['string'][$userrequest]);
					wp_redirect( $redirect, 301 );
					exit();
				}
			}
		}

		public function do_regex_redirect() {
			if ( !is_404() ) {
				return; // don't redirect away from an existing page
			}
			$redirects = get_option( self::OPTION, array() );
			$userrequest = $this->get_normalized_path();
			if ( !empty( $redirects['regex'] ) ) {
				foreach ( $redirects['regex'] as $pattern => $redirect ) {
					if ( preg_match("#^$pattern#", $userrequest, $matches) ||
						preg_match("#^$pattern#", urldecode($userrequest), $matches) ) {
						require_once( __DIR__ . '/Tribe_301_MatchesMapRegex.php' );
						$redirect = Tribe_301_MatchesMapRegex::apply($redirect, $matches);
						$this->remove_nocache_headers();
						$redirect = $this->append_query_string( $redirect );
						wp_redirect( $redirect, 301 );
						exit();
					}
				}
			}
		}

		private function remove_nocache_headers() {
			if ( !function_exists( 'header_remove' ) ) {
				return;
			}
			$headers = wp_get_nocache_headers();
			foreach ( $headers as $key => $value ) {
				header_remove( $key );
			}
		}

		public function migrate_simple_301_redirects() {
			require_once( __DIR__ . '/Simple_301_Redirects_Migrator.php' );
			$migrator = new Simple_301_Redirects_Migrator();
			if ( $migrator->needs_migration() ) {
				$migrator->migrate();
			}
		}

		/**
		 * utility function to get the normalized address of the current request
		 * credit: http://www.phpro.org/examples/Get-Full-URL.html
		 */
		private function get_normalized_path() {
			global $wp;
			$path = $wp->request;
			if ( strpos($path, '/' ) !== 0 ) {
				$path = '/'.$path;
			}
			return $path;
		}

		private function append_query_string( $url ) {
			if ( !empty( $_GET ) ) {
				$url = add_query_arg( $_GET, $url );
			}
			return $url;
		}

		public static function instance() {
			if ( empty( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public static function init() {
			self::instance()->add_hooks();
		}

		private function add_hooks() {
			add_action( 'init', array( $this, 'migrate_simple_301_redirects' ), 11, 0 );
			add_action( 'parse_request', array( $this, 'do_static_redirect' ), 1, 1 );
			add_action( 'template_redirect', array( $this, 'do_regex_redirect' ), 1, 0 );

			if ( is_admin() ) {
				include( __DIR__ . '/Tribe_301_Admin.php' );
				Tribe_301_Admin::init();
			}
		}

	}

	add_action( 'plugins_loaded', array( 'Tribe_301_Redirects', 'init' ) );

}
