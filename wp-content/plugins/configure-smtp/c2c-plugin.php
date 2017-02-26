<?php
/**
 * @package C2C_Plugins
 * @author Scott Reilly
 * @version 023
 */
/*
Basis for other plugins

Compatible with WordPress 3.0+, 3.1+, 3.2+.

=>> Read the accompanying readme.txt file for more information.  Also, visit the plugin's homepage
=>> for more information and the latest updates

Installation:

*/

/*
Copyright (c) 2010-2011 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy,
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

if ( !class_exists( 'C2C_Plugin_023' ) ) :

abstract class C2C_Plugin_023 {
	protected $plugin_css_version = '007';
	protected $options = array();
	protected $option_names = array();
	protected $required_config = array( 'menu_name', 'name' );
	protected $saved_settings = false;
	protected $saved_settings_msg = '';

	/**
	 * Handles installation tasks, such as ensuring plugin options are instantiated and saved to options table.
	 *
	 * @param string $version Version of the plugin.
	 * @param string $id_base A unique base ID for the plugin (generally a lower-case, dash-separated version of plugin name).
	 * @param string $author_prefix Short (2-3 char) identifier for plugin author.
	 * @param string $file The __FILE__ value for the sub-class.
	 * @param array $plugin_options (optional) Array specifying further customization of plugin configuration.
	 * @return void
	 */
	public function C2C_Plugin_023( $version, $id_base, $author_prefix, $file, $plugin_options = array() ) {
		global $pagenow;
		$id_base = sanitize_title( $id_base );
		if ( !file_exists( $file ) )
			die( sprintf( __( 'Invalid file specified for C2C_Plugin: %s', $this->textdomain ), $file ) );

		$u_id_base = str_replace( '-', '_', $id_base );
		$author_prefix .= '_';
		$defaults = array(
			'admin_options_name'	=> $author_prefix . $u_id_base,	// The setting under which all plugin settings are stored under (as array)
			'config'				=> array(),						// Default configuration
			'disable_contextual_help' => false,						// Prevent overriding of the contextual help?
			'disable_update_check'	=> false,						// Prevent WP from checking for updates to this plugin?
			'hook_prefix'			=> $u_id_base . '_',			// Prefix for all hooks
			'form_name'				=> $u_id_base,					// Name for the <form>
			'menu_name'				=> '',							// Specify this via plugin
			'name'					=> '',							// Full, localized version of the plugin name
			'nonce_field'			=> 'update-' . $u_id_base,		// Nonce field value
			'settings_page'			=> 'options-general',			// The type of the settings page.
			'show_admin'			=> true,						// Should admin be shown? Only applies if admin is enabled
			'textdomain'			=> $id_base,					// Textdomain for localization
			'textdomain_subdir'		=> 'lang'						// Subdirectory, relative to plugin's root, to hold localization files
		);
		$settings = wp_parse_args( $plugin_options, $defaults );

		foreach ( array_keys( $defaults ) as $key )
			$this->$key = $settings[$key];

		$this->author_prefix		= $author_prefix;
		$this->id_base				= $id_base;
		$this->options_page			= ''; // This will be set when the options is created
		$this->plugin_basename		= plugin_basename( $file );
		$this->plugin_file			= $file;
		$this->plugin_path			= plugins_url( '', $file );
		$this->u_id_base			= $u_id_base; // Underscored version of id_base
		$this->version				= $version;

		add_action( 'init',				array( &$this, 'init' ) );
		$plugin_file = implode( '/', array_slice( explode( '/', $this->plugin_basename ), -2 ) );
		add_action( 'activate_' . $plugin_file,		array( &$this, 'install' ) );
		add_action( 'deactivate_' . $plugin_file,	array( &$this, 'deactivate' ) );

		add_action( 'admin_init',					array( &$this, 'init_options' ) );

		if ( basename( $pagenow, '.php' ) == $this->settings_page )
			add_action( 'admin_head', 				array( &$this, 'add_c2c_admin_css' ) );
	}

	/**
	 * Handles installation tasks, such as ensuring plugin options are instantiated and saved to options table.
	 *
	 * This can be overridden.
	 *
	 * @return void
	 */
	public function install() {
		$this->options = $this->get_options();
		update_option( $this->admin_options_name, $this->options );
	}

	/**
	 * Handles deactivation tasks
	 *
	 * This should be overridden.
	 *
	 * @return void
	 */
	public function deactivate() { }

	/**
	 * Handles actions to be hooked to 'init' action, such as loading text domain and loading plugin config data array.
	 *
	 * @return void
	 */
	public function init() {
		global $c2c_plugin_max_css_version;
		if ( !isset( $c2c_plugin_max_css_version ) || ( $c2c_plugin_max_css_version < $this->plugin_css_version ) )
			$c2c_plugin_max_css_version = $this->plugin_css_version;
		$this->load_textdomain();
		$this->load_config();
		$this->verify_config();

		if ( $this->disable_update_check )
			add_filter( 'http_request_args', array( &$this, 'disable_update_check' ), 5, 2 );

		if ( $this->show_admin && $this->settings_page && !empty( $this->config ) && current_user_can( 'manage_options' ) ) {
			add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
			if ( !$this->disable_contextual_help ) {
				add_action( 'contextual_help', array( &$this, 'contextual_help' ), 10, 3 );
				if ( $this->is_plugin_admin_page() )
					add_thickbox();
			}
		}

		$this->register_filters();
	}

	/**
	 * Checks to see if the plugin has been upgraded from an earlier version.
	 *
	 * Calls handle_plugin_update() if an upgrade was detected. Override that
	 * to do whatever needs done to bring older settings, etc up-to-date.
	 *
	 * @since 021
	 */
	function check_if_plugin_was_upgraded() {
		$_version = isset( $this->options['_version'] ) ? $this->options['_version'] : '0.0';
		if ( $_version != $this->version ) {
			// Save the original options into another option in case something goes wrong.
			// TODO: Currently just saves one version back... should it save more?
			update_option( 'bkup_' . $this->admin_options_name, $this->options );

			$this->options['_version'] = $this->version;
			$options = $this->handle_plugin_upgrade( $_version, $this->options );
			update_option( $this->admin_options_name, $options );
			$this->options = $options;
		}
	}

	/**
	 * Handle plugin updates.  (To be implemented by inheriting class, if
	 * necessary.)
	 *
	 * Intended to be used for updating plugin options, etc.
	 *
	 * This is only called if the version stored in the db doesn't match the
	 * plugin's current version.  At the very least the settings will get
	 * re-saved so that the new current version can be recorded.
	 *
	 * @since 021
	 * @param string $old_version The version number of the old version of
	 *        the plugin. '0.0' indicates no version previously stored
	 * @param array $options Array of all plugin options
	 */
	protected function handle_plugin_upgrade( $old_version, $options ) {
		/* Example:
		if ( version_compare( '1.2', $old_version ) > 0 ) {
			// Plugin got upgraded from a version earlier than 1.2
			// Which (for this example) is when a minimum value got raised
			if ( $options['min_value'] < 5 )
				$options['min_value'] = 5;
		}
		*/
		return $options; // Important!
	}

	/**
	 * Prevents this plugin from being included when WordPress phones home
	 * to check for plugin updates.
	 *
	 * @param array $r Response array
	 * @param string $url URL for the update check
	 * @return array The response array with this plugin removed, if present
	 */
	public function disable_update_check( $r, $url ) {
		if ( 0 !== strpos( $url, 'http://api.wordpress.org/plugins/update-check' ) )
			return $r; // Not a plugin update request. Bail immediately.
		$plugins = unserialize( $r['body']['plugins'] );
		unset( $plugins->plugins[ plugin_basename( __FILE__ ) ] );
		unset( $plugins->active[ array_search( plugin_basename( __FILE__ ), $plugins->active ) ] );
		$r['body']['plugins'] = serialize( $plugins );
		return $r;
	}

	/**
	 * Initializes options
	 *
	 * @return void
	 */
	public function init_options() {
		register_setting( $this->admin_options_name, $this->admin_options_name, array( &$this, 'sanitize_inputs' ) );
		add_settings_section( 'default', '', array( &$this, 'draw_default_section' ), $this->plugin_file );
		add_filter( 'whitelist_options', array( &$this, 'whitelist_options' ) );
		foreach ( $this->get_option_names( false ) as $opt )
			add_settings_field( $opt, $this->get_option_label( $opt ), array( &$this, 'display_option' ), $this->plugin_file, 'default', $opt );
	}

	/**
	 * Whitelist the plugin's option(s)
	 *
	 * @param array $options Array of options
	 * @return array The whitelist-amended $options array
	 */
	public function whitelist_options( $options ) {
		$added = array( $this->admin_options_name => array( $this->admin_options_name ) );
		$options = add_option_whitelist( $added, $options );
		return $options;
	}

	/**
	 * Special output for the default section. Can be overridden if desired.
	 *
	 * @return void
	 */
	public function draw_default_section() { }

	/**
	 * Gets the label for a given option
	 *
	 * @param string $opt The option
	 * @return string The label for the option
	 */
	public function get_option_label( $opt ) {
		return isset( $this->config[$opt]['label'] ) ? $this->config[$opt]['label'] : '';
	}

	/**
	 * Resets plugin options
	 *
	 * @return array
	 */
	public function reset_options() {
		$options = $this->get_options( false );
		return $options;
	}

	/**
	 * Sanitize user inputs prior to saving
	 */
	public function sanitize_inputs( $inputs ) {
		do_action( $this->get_hook( 'before_save_options' ), $this );
		if ( isset( $_POST['Reset'] ) ) {
			$options = $this->reset_options();
			add_settings_error( 'general', 'settings_reset', __( 'Settings reset.', $this->textdomain ), 'updated' );
		} else {
			// Start with the existing options, then start overwriting their potential override value. (This prevents
			// unscrupulous addition of fields by the user)
			$options = $this->get_options();
			$option_names = $this->get_option_names();
			foreach ( $option_names as $opt ) {
				if ( !isset( $inputs[$opt] ) ) {
					if ( $this->config[$opt]['input'] == 'checkbox' )
						$options[$opt] = '';
					elseif ( ( $this->config[$opt]['required'] === true ) ) {
						$msg = sprintf( __( 'A value is required for: "%s"', $this->textdomain ), $this->config[$opt]['label'] );
						add_settings_error( 'general', 'setting_required', $msg, 'error' );
					}
				}
				else {
					$val = $inputs[$opt];
					$error = false;
					if ( empty( $val ) && ( $this->config[$opt]['required'] === true ) ) {
						$msg = sprintf( __( 'A value is required for: "%s"', $this->textdomain ), $this->config[$opt]['label'] );
						$error = true;
					} else {
						$input = $this->config[$opt]['input'];
						switch ( $this->config[$opt]['datatype'] ) {
							case 'checkbox':
								break;
							case 'int':
								if ( !empty( $val ) && ( !is_numeric( $val ) || ( intval( $val ) != round( $val ) ) ) ) {
									$msg = sprintf( __( 'Expected integer value for: %s', $this->textdomain ), $this->config[$opt]['label'] );
									$error = true;
									$val = '';
								}
								break;
							case 'array':
								if ( empty( $val ) )
									$val = array();
								elseif ( is_array( $val ) )
									$val = array_map( 'trim', $val );
								elseif ( $input == 'text' )
									$val = explode( ',', str_replace( array( ', ', ' ', ',' ), ',', $val ) );
								else
									$val = array_map( 'trim', explode( "\n", trim( $val ) ) );
								break;
							case 'hash':
								if ( !empty( $val ) && $input != 'select' && !is_array( $val ) ) {
									$new_values = array();
									foreach ( explode( "\n", $val ) AS $line ) {
										list( $shortcut, $text ) = array_map( 'trim', explode( "=>", $line, 2 ) );
										if ( !empty( $shortcut ) )
											$new_values[str_replace( '\\', '', $shortcut )] = str_replace( '\\', '', $text );
									}
									$val = $new_values;
								}
								break;
						}
					}
					if ( $error )
						add_settings_error( 'general', 'setting_not_int', $msg, 'error' );
					$options[$opt] = $val;
				}
			}
			$options = apply_filters( $this->get_hook( 'before_update_option' ), $options, $this );
		}
		$options['_version'] = $this->version;
		return $options;
	}

	/**
	 * Initializes the plugin's configuration and localizable text variables.
	 *
	 * @return void
	 */
	abstract protected function load_config();

	/**
	 * Verify that the necessary configuration files were set in the inheriting class.
	 *
	 * @return void
	 */
	protected function verify_config() {
		// Ensure required configuration options have been configured via the sub-class.  Die if any aren't.
		foreach ( $this->required_config as $config ) {
			if ( empty( $this->$config ) )
				die( "The plugin configuration option '$config' must be supplied." );
		}
		// Set/change configuration options based on sub-class changes.
		if ( empty( $this->config ) )
			$this->show_admin = false;
		else {
			// Initialize any option attributes that weren't specified by the plugin
			foreach ( $this->get_option_names( true ) as $opt ) {
				foreach ( array( 'datatype', 'default', 'help', 'input', 'input_attributes', 'label', 'no_wrap', 'options', 'output', 'required' ) as $attrib ) {
					if ( !isset( $this->config[$opt][$attrib] ) )
						$this->config[$opt][$attrib] = '';
				}
				$this->config[$opt]['allow_html'] = false;
				$this->config[$opt]['class'] = array();
			}
		}
	}

	/**
	 * Loads the localization textdomain for the plugin.
	 *
	 * @return void
	 */
	protected function load_textdomain() {
		$subdir = empty( $this->textdomain_subdir ) ? '' : '/'.$this->textdomain_subdir;
		load_plugin_textdomain( $this->textdomain, false, basename( dirname( $this->plugin_file ) ) . $subdir );
	}

	/**
	 * Registers filters.
	 * NOTE: This occurs during the 'init' filter, so you can't use this to hook anything that happens earlier
	 *
	 * @return void
	 */
	public function register_filters() {
		// This should be overridden in order to define filters.
	}

	/**
	 * Outputs simple contextual help text, comprising solely of a thickboxed link to the plugin's hosted readme.txt file.
	 *
	 * NOTE: If overriding this in a sub-class, before sure to include the
	 * check at the beginning of the function to ensure it shows up on its
	 * own settings admin page.
	 *
	 * @param string $contextual_help The default contextual help
	 * @param int $screen_id The screen ID
	 * @param object $screen The screen object (only supplied in WP 3.0)
	 * @return void (Text is echoed)
	 */
	public function contextual_help( $contextual_help, $screen_id, $screen = null ) {
		if ( $screen_id != $this->options_page )
			return $contextual_help;

		$help_url = admin_url( "plugin-install.php?tab=plugin-information&amp;plugin={$this->id_base}&amp;TB_iframe=true&amp;width=640&amp;height=656" );

		echo '<p class="more-help">';
		echo '<a title="' . esc_attr( sprintf( __( 'More information about %1$s %2$s', $this->textdomain ), $this->name, $this->version ) ) .
			'" class="thickbox" href="' . $help_url . '">' . __( 'Click for more help on this plugin', $this->textdomain ) . '</a>' .
			__( ' (especially check out the "Other Notes" tab, if present)', $this->textdomain );
		echo ".</p>\n";
		return;
	}

	/**
	 * Outputs CSS into admin head of the plugin's settings page
	 *
	 * @return void
	 */
	public function add_c2c_admin_css() {
		global $c2c_plugin_max_css_version, $c2c_plugin_css_was_output;
		if ( ( $c2c_plugin_max_css_version != $this->plugin_css_version ) || ( isset( $c2c_plugin_css_was_output ) && $c2c_plugin_css_was_output ) )
			return;
		$c2c_plugin_css_was_output = true;
		$logo = plugins_url( 'c2c_minilogo.png', $this->plugin_file );
		/**
		 * Remember to increment the plugin_css_version variable if changing the CSS
		 */
		echo <<<CSS
		<style type="text/css">
		.long-text {width:95% !important;}
		#c2c {
			text-align:center;
			color:#888;
			background-color:#ffffef;
			padding:5px 0 0;
			margin-top:12px;
			border-style:solid;
			border-color:#dadada;
			border-width:1px 0;
		}
		#c2c div {
			margin:0 auto;
			padding:5px 40px 0 0;
			width:45%;
			min-height:40px;
			background:url('$logo') no-repeat top right;
		}
		#c2c span {
			display:block;
			font-size:x-small;
		}
		.form-table {margin-bottom:20px;}
		.c2c-plugin-list {margin-left:2em;}
		.c2c-plugin-list li {list-style:disc outside;}
		.wrap {margin-bottom:30px !important;}
		.c2c-form input[type="checkbox"] {width:1.5em;}
		.c2c-form .hr, .c2c-hr {border-bottom:1px solid #ccc;padding:0 2px;margin-bottom:6px;}
		.c2c-input-help {color:#777;font-size:x-small;}
		.c2c-fieldset {border:1px solid #ccc; padding:2px 8px;}
		.c2c-textarea, .c2c-inline_textarea {width:95%;font-family:"Courier New", Courier, mono;}
		.c2c-nowrap {
			white-space:nowrap;overflow:auto;
		}
		.see-help {font-size:x-small;font-style:italic;}
		.more-help {display:block;margin-top:8px;}
		</style>

CSS;
	}

	/**
	 * Registers the admin options page and the Settings link.
	 *
	 * @return void
	 */
	public function admin_menu() {
		add_filter( 'plugin_action_links_' . $this->plugin_basename, array( &$this, 'plugin_action_links' ) );
		switch ( $this->settings_page ) {
			case 'options-general' :
				$func_root = 'options';
				break;
			case 'themes' :
				$func_root = 'theme';
				break;
			default :
				$func_root = $this->settings_page;
		}
		$menu_func = 'add_' . $func_root . '_page';
		if ( function_exists( $menu_func ) )
			$this->options_page = call_user_func( $menu_func, $this->name, $this->menu_name, 'manage_options', $this->plugin_basename, array( &$this, 'options_page' ) );
	}

	/**
	 * Adds a 'Settings' link to the plugin action links.
	 *
	 * @param int $limit The default limit value for the current posts query.
	 * @return array Links associated with a plugin on the admin Plugins page
	 */
	public function plugin_action_links( $action_links ) {
		$settings_link = '<a href="' . $this->settings_page . '.php?page='.$this->plugin_basename.'">' . __( 'Settings', $this->textdomain ) . '</a>';
		array_unshift( $action_links, $settings_link );
		return $action_links;
	}

	/**
	 * See if the setting is pertinent to this version of WP
	 *
	 * @since 013
	 *
	 * @param string $opt The option name
	 * @return bool If the option is valid for this version of WP
	 */
	protected function is_option_valid( $opt ) {
		global $wp_version;
		$valid = true;
		$ver_operators = array( 'wpgt' => '>', 'wpgte' => '>=', 'wplt' => '<', 'wplte' => '<=' );
		foreach ( $ver_operators as $ver_check => $ver_op ) {
			if ( isset( $this->config[$opt][$ver_check] )
				&& !empty( $this->config[$opt][$ver_check] )
				&& !version_compare( $wp_version, $this->config[$opt][$ver_check], $ver_op ) ) {
					$valid = false;
					break;
			}
		}
		return $valid;
	}

	/**
	 * Returns the list of option names.
	 *
	 * @param bool $include_non_options (optional) Should non-options be included? Default is false.
	 * @return array Array of option names.
	 */
	protected function get_option_names( $include_non_options = false ) {
		if ( !$include_non_options && !empty( $this->option_names ) )
			return $this->option_names;
		if ( $include_non_options )
			return array_keys( $this->config );
		$this->option_names = array();
		foreach ( array_keys( $this->config ) as $opt ) {
			if ( isset( $this->config[$opt]['input'] ) && $this->config[$opt]['input'] != '' && $this->config[$opt]['input'] != 'none' && $this->is_option_valid( $opt ) )
				$this->option_names[] = $opt;
		}
		return $this->option_names;
	}

	/**
	 * Returns either the buffered array of all options for the plugin, or
	 * obtains the options and buffers the value.
	 *
	 * @param bool $with_current_values (optional) Should the currently saved values be returned? If false, then the plugin's defaults are returned. Default is true.
	 * @return array The options array for the plugin (which is also stored in $this->options if !$with_options).
	 */
	protected function get_options( $with_current_values = true ) {
		if ( $with_current_values && !empty( $this->options ) )
			return $this->options;
		// Derive options from the config
		$options = array();
		$option_names = $this->get_option_names( !$with_current_values );
		foreach ( $option_names as $opt )
			$options[$opt] = $this->config[$opt]['default'];
		if ( !$with_current_values )
			return $options;
		$this->options = wp_parse_args( get_option( $this->admin_options_name ), $options );

		// Check to see if the plugin has been updated
		$this->check_if_plugin_was_upgraded();

		// Un-escape fields
		foreach ( $option_names as $opt ) {
			if ( $this->config[$opt]['allow_html'] == true ) {
				if ( is_array( $this->options[$opt] ) ) {
					foreach ( $this->options[$opt] as $key => $val ) {
						$new_key = wp_specialchars_decode( $key, ENT_QUOTES );
						$new_val = wp_specialchars_decode( $val, ENT_QUOTES );
						$this->options[$opt][$new_key] = $new_val;
						if ( $key != $new_key )
							unset( $this->options[$opt][$key] );
					}
				} else {
					$this->options[$opt] = wp_specialchars_decode( $this->options[$opt], ENT_QUOTES );
				}
			}
		}
		return apply_filters( $this->get_hook( 'options' ), $this->options );
	}

	/**
	 * Gets the name to use for a form's <input type="hidden" name="XXX" value="1" />
	 *
	 * @param string $prefix A prefix string, unique to the form
	 * @return string The name
	 */
	protected function get_form_submit_name( $prefix ) {
		return $prefix . '_' . $this->u_id_base;
	}

	/**
	 * Returns the URL for a plugin's form to use for its action attribute
	 *
	 * @return string The action URL
	 */
	protected function form_action_url() {
		return $_SERVER['PHP_SELF'] . '?page=' . $this->plugin_basename;
	}

	/**
	 * Checks if the plugin's settings page has been submitted.
	 *
	 * @param string $prefix The prefix for the form's unique submit hidden input field
	 * @return bool True if the plugin's settings have been submitted for saving, else false.
	 */
	protected function is_submitting_form( $prefix ) {
		return ( isset( $_POST['option_page'] ) && ( $_POST['option_page'] == $this->admin_options_name ) );
	}

	/**
	 * Checks if the current page is the plugin's settings page.
	 *
	 * @return bool True if on the plugin's settings page, else false.
	 */
	protected function is_plugin_admin_page() {
		global $pagenow;
		return ( basename( $pagenow, '.php' ) == $this->settings_page && isset( $_REQUEST['page'] ) && $_REQUEST['page'] == $this->plugin_basename );
	}

	/**
	 * Outputs the markup for an option's form field (and surrounding markup)
	 *
	 * @param string $opt The name/key of the option.
	 * @return void
	 */
	public function display_option( $opt ) {
		do_action( $this->get_hook( 'pre_display_option' ), $opt );

		$options = $this->get_options();

		foreach ( array( 'datatype', 'input' ) as $attrib )
			$$attrib = isset( $this->config[$opt][$attrib] ) ? $this->config[$opt][$attrib] : '';

		if ( $input == '' || $input == 'none' )
			return;
		elseif ( $input == 'custom' ) {
			do_action( $this->get_hook( 'custom_display_option' ), $opt );
			return;
		}
		$value = isset( $options[$opt] ) ? $options[$opt] : '';
		$popt = $this->admin_options_name . "[$opt]";
		if ( $input == 'multiselect' ) {
			// Do nothing since it needs the values as an array
			$popt .= '[]';
		} elseif ( $datatype == 'array' ) {
			if ( !is_array( $value ) )
				$value = '';
			else {
				if ( $input == 'textarea' || $input == 'inline_textarea' )
					$value = implode( "\n", $value );
				else
					$value = implode( ', ', $value );
			}
		} elseif ( $datatype == 'hash' && $input != 'select' ) {
			if ( !is_array( $value ) )
				$value = '';
			else {
				$new_value = '';
				foreach ( $value AS $shortcut => $replacement )
					$new_value .= "$shortcut => $replacement\n";
				$value = $new_value;
			}
		}
		$attributes = $this->config[$opt]['input_attributes'];
		$this->config[$opt]['class'][] = 'c2c-' . $input;
		if ( ( 'textarea' == $input || 'inline_textarea' == $input ) && $this->config[$opt]['no_wrap'] ) {
			$this->config[$opt]['class'][] = 'c2c-nowrap';
			$attributes .= ' wrap="off"'; // Unfortunately CSS is not enough
		}
		elseif ( in_array( $input, array( 'text', 'long_text', 'short_text' ) ) ) {
			$this->config[$opt]['class'][]  = ( ( $input == 'short_text' ) ? 'small-text' : 'regular-text' );
			if ( $input == 'long_text' )
				$this->config[$opt]['class'][] = ' long-text';
		}
		$class = implode( ' ', $this->config[$opt]['class'] );
		$attribs = "name='$popt' id='$opt' class='$class' $attributes";
		if ( $input == '' ) {
// Change of implementation prevents this from being possible (since this function only gets called for registered settings)
//			if ( !empty( $this->config[$opt]['output'] ) )
//				echo $this->config[$opt]['output'] . "\n";
//			else
//				echo '<div class="hr">&nbsp;</div>' . "\n";
		} elseif ( $input == 'textarea' || $input == 'inline_textarea' ) {
			if ( $input == 'textarea' )
				echo "</td><tr><td colspan='2'>";
			echo "<textarea $attribs>$value</textarea>\n";
		} elseif ( $input == 'select' ) {
			echo "<select $attribs>";
			if ( $this->config[$opt]['datatype'] == 'hash' ) {
				foreach ( (array) $this->config[$opt]['options'] as $sopt => $sval )
					echo "<option value='$sopt' " . selected( $value, $sopt, false ) . ">$sval</option>\n";
			} else {
				foreach ( (array) $this->config[$opt]['options'] as $sopt )
					echo "<option value='$sopt' " . selected( $value, $sopt, false ) . ">$sopt</option>\n";
			}
			echo "</select>";
		} elseif ( $input == 'multiselect' ) {
			echo '<fieldset class="c2c-fieldset">' . "\n";
			foreach ( (array) $this->config[$opt]['options'] as $sopt )
				echo "<input type='checkbox' $attribs value='$sopt' " . checked( in_array( $sopt, $value ), true, false ) . ">$sopt</input><br />\n";
			echo '</fieldset>';
		} elseif ( $input == 'checkbox' ) {
			echo "<input type='$input' $attribs value='1' " . checked( $value, 1, false ) . " />\n";
		} else { // Only 'text' and 'password' should fall through to here.
			echo "<input type='$input' $attribs value='" . esc_attr( $value ) . "' />\n";
		}
		if ( $help = apply_filters( $this->get_hook( 'option_help'), $this->config[$opt]['help'], $opt ) )
			echo "<br /><span class='c2c-input-help'>$help</span>\n";

		do_action( $this->get_hook( 'post_display_option' ), $opt );
	}

	/**
	 * Outputs the descriptive text (and h2 heading) for the options page.
	 *
	 * Intended to be overridden by sub-class.
	 *
	 * @param string $localized_heading_text (optional) Localized page heading text.
	 * @return void
	 */
	protected function options_page_description( $localized_heading_text = '' ) {
		if ( empty( $localized_heading_text ) )
			$localized_heading_text = $this->name;
		if ( $localized_heading_text )
			echo '<h2>' . $localized_heading_text . "</h2>\n";
		if ( !$this->disable_contextual_help )
			echo '<p class="see-help">' . __( 'See the "Help" link to the top-right of the page for more help.', $this->textdomain ) . "</p>\n";
	}

	/**
	 * Outputs the options page for the plugin, and saves user updates to the
	 * options.
	 *
	 * @return void
	 */
	public function options_page() {
		$options = $this->get_options();

		if ( function_exists( 'settings_errors' ) ) // Check for pre-3.0 compatibility
			settings_errors();

		if ( $this->saved_settings )
			echo "<div id='message' class='updated fade'><p><strong>" . $this->saved_settings_msg . '</strong></p></div>';

		$logo = plugins_url( 'c2c_minilogo.png', $this->plugin_file );

		echo "<div class='wrap'>\n";
		echo "<div class='icon32' style='width:44px;'><img src='$logo' alt='" . esc_attr__( 'A plugin by coffee2code', $this->textdomain ) . "' /><br /></div>\n";

		$this->options_page_description();

		do_action( $this->get_hook( 'before_settings_form' ), $this );

		echo "<form action='options.php' method='post' class='c2c-form'>\n";

		settings_fields( $this->admin_options_name );
		do_settings_sections( $this->plugin_file );

		echo '<input type="submit" name="Submit" class="button-primary" value="' . esc_attr__( 'Save Changes', $this->textdomain ) . '" />' . "\n";
		echo '<input type="submit" name="Reset" class="button" value="' . esc_attr__( 'Reset Settings', $this->textdomain ) . '" />' . "\n";
		echo '</form>' . "\n";

		do_action( $this->get_hook( 'after_settings_form' ), $this );

		echo '<div id="c2c" class="wrap"><div>' . "\n";
		$c2c = '<a href="http://coffee2code.com" title="coffee2code.com">' . __( 'Scott Reilly, aka coffee2code', $this->textdomain ) . '</a>';
		echo sprintf( __( 'This plugin brought to you by %s.', $this->textdomain ), $c2c );
		echo '<span><a href="http://coffee2code.com/donate" title="' . esc_attr__( 'Please consider a donation', $this->textdomain ) . '">' .
		__( 'Did you find this plugin useful?', $this->textdomain ) . '</a></span>';
		echo '</div></div>' . "\n";
	}

	/**
	 * Returns the full plugin-specific name for a hook.
	 *
	 * @param string $hook The name of a hook, to be made plugin-specific.
	 * @return string The plugin-specific version of the hook name.
	 */
	protected function get_hook( $hook ) {
		return $this->hook_prefix . '_' . $hook;
	}

	/**
	 * Returns the URL for the plugin's readme.txt file on wordpress.org/extend/plugins
	 *
	 * @since 005
	 *
	 * @return string The URL
	 */
	public function readme_url() {
		return 'http://wordpress.org/extend/plugins/' . $this->id_base . '/tags/' . $this->version . '/readme.txt';
	}
} // end class

endif; // end if !class_exists()

?>