<?php
/*
 Plugin Name: Debug Bar Extender
 Plugin URI: http://wordpress.org/extend/plugins/debug-bar-extender/
 Description: A minimalistic profiler / debugging class that hooks into the debug bar and can be implemented easily
 Author: Thorsten Ott, Automattic
 Version: 0.5
 Author URI: http://hitchhackerguide.com
 */
 
/**
 * Usage example to debug the loop of a theme:
 *
 *
 * In the index.php add your checkpoints
 *
 * ...
 * <?php if (have_posts()) : ?>
 *
 * <?php dbgx_checkpoint('loop start'); ?>
 *
 * <?php while (have_posts()) : the_post(); ?>
 * 
 * <?php dbgx_trace_var( $post ); ?>
 * <?php dbgx_checkpoint('loop1'); ?>
 *
 * <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
 * <?php //before_post(); ?>
 * <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'kubrick'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h2>
 * <small><?php the_time(get_option('date_format')) ?> by <?php the_author() ?></small>
 * <div class="entry">
 * <?php the_content(__('Read the rest of this entry &raquo;', 'kubrick')); ?>
 * </div>
 * <p class="postmetadata"><?php the_tags(__('Tags:', 'kubrick') . ' ', ', ', '<br />'); ?> <?php printf(__('Posted in %s', 'kubrick'), get_the_category_list(', ')); ?> | <?php edit_post_link(__('Edit', 'kubrick'), '', ' | '); ?> <?php comments_popup_link(__('No Comments &#187;', 'kubrick'), __('1 Comment &#187;', 'kubrick'), __('% Comments &#187;', 'kubrick')); ?></p>
 * <?php after_post(); ?>
 * </div>
 *
 * <?php dbgx_checkpoint('loop2'); ?>
 *
 * <?php endwhile; ?>
 *
 * <?php dbgx_checkpoint('loop end'); ?>
 *
 * <div class="navigation">
 * <div class="alignleft"><?php next_posts_link(__('&laquo; Older Entries', 'kubrick')) ?></div>
 * <div class="alignright"><?php previous_posts_link(__('Newer Entries &raquo;', 'kubrick')) ?></div>
 * </div>
 * <?php else : ?>
 * ...
 *
 */
class Debug_Bar_Extender {

	private static $__instance = NULL;

	private $start_time = 0;
	private $previous_time = 0;
	private $current_time = 0;
	private $elapsed_time = 0;
	private $end_time = 0;

	private $use_error_log = false;
	
	private $settings = array();
	private $default_settings = array();
	private $settings_texts = array();
	
	private $profile_data = array();
	private $memcache_lookup = array();
	private $variable_lookup = array();
	
	
	public function __construct() {
		add_action( 'admin_init', array( &$this, 'register_setting' ) );
		add_action( 'admin_menu', array( &$this, 'register_settings_page' ) );
		
		$this->default_settings = (array) apply_filters( 'debugbarextender_default_settings', array(
			'enable_profiler'				=> 1,
			'enable_variable_lookup'		=> 1,
			'track_default_vars'			=> 1,
			'add_default_checkpoints'		=> 1,
			'savequeries_debugmode_enable'	=> 1,
			'use_error_log'					=> 0,
			'allow_url_settings'			=> 0,
			'enable_admin_bar_menu'			=> 0,
		) );
		
		$this->settings_texts = (array) apply_filters( 'debugbarextender_settings_texts', array(
			'enable_profiler'				=> array( 'label' => 'Enable profiler', 'desc' => 'You can measure runtimes between checkpoints via <code>Debug_Bar_Extender::instance()->start( $note="" )</code>, <code>Debug_Bar_Extender::instance()->checkpoint( $note="" )</code>,  <code>Debug_Bar_Extender::instance()->end( $note="" )</code> or by using the wrapper function <code>dbgx_checkpoint( $note="" )</code>.' ),
			'enable_variable_lookup'		=> array( 'label' => 'Enable variable lookup', 'desc' => 'You can screen variables via <code>Debug_Bar_Extender::instance()->trace_var( $value, $var_name = false )</code> or via <code>dbgx_trace_var( $value, $var_name = false )</code>. Omitting the <code>$var_name</code> will let the script look it up. Please be aware that sizes are approximate based on <code>strlen(serialize())</code>' ),
			'track_default_vars'			=> array( 'label' => 'Track default variables', 'desc' => 'Track various useful variables useful for the regular debugging.' ),
			'add_default_checkpoints'		=> array( 'label' => 'Add default checkpoints', 'desc' => 'Enabling this option will add various default checkpoints to be used with the profiler.' ),
			'savequeries_debugmode_enable'	=> array( 'label' => 'Try setting debug mode', 'desc' => 'Try setting <code>WP_DEBUG</code> and <code>SAVEQUERIES</code> variables to enable additional debug menus. This should be generally done via <code>wp-config.php</code>, but this setting is worth a shot.' ),
			'use_error_log'					=> array( 'label' => 'Use <code>error_log()</code> reporting', 'desc' => 'Log the results using <code>error_log()</code> as well. This helps when debugging sessions which are not producing any admin bar output.' ),
			'allow_url_settings'			=> array( 'label' => 'Allow setting alteration via query parameters', 'desc' => 'Allow <code>dbgx_use_error_log</code>, <code>dbgx_track_default_vars</code>, <code>dbgx_add_default_checkpoints</code> to be added as query strings in order to enable respective features. Combinations are possible - for example: ' . add_query_arg( array( 'dbgx_use_error_log' => 1, 'dbgx_add_default_checkpoints' => 1, 'dbgx_track_default_vars' => 1 ) ) ),
			'enable_admin_bar_menu'			=> array( 'label' => 'Enable admin bar menu', 'desc' => 'Adds a menu to your admin bar to allow access to the url query parameters to control debug bar behaviour, only useful with allow_url_settings enabled' ),
		) );
		
		$user_settings = get_option( 'debugbarextender_settings' );
		if ( false === $user_settings )
			$user_settings = array();
			
		$this->settings = wp_parse_args( $user_settings, $this->default_settings );	
	}
	
	public static function init() {	
		if ( 1 == Debug_Bar_Extender::instance()->settings['savequeries_debugmode_enable'] ) {
			if ( !defined( 'SAVEQUERIES' ) )
				define( 'SAVEQUERIES', true );
			if ( !defined( 'WP_DEBUG' ) )
				define( 'WP_DEBUG', true );
		}
		
		if ( 1 == Debug_Bar_Extender::instance()->settings['use_error_log'] || ( 1 == Debug_Bar_Extender::instance()->settings['allow_url_settings'] && isset( $_GET['dbgx_use_error_log'] ) && 1 == $_GET['dbgx_use_error_log'] ) ) {
			Debug_Bar_Extender::instance()->use_error_log = true;
		}
		
		if ( Debug_Bar_Extender::instance()->use_error_log )
			error_log( 'Starting debug session for ' . $_SERVER['REQUEST_URI'] );
		
		Debug_Bar_Extender::instance()->prepare_debug_menu();
	}
	
	/*
	 * Use this singleton to address methods
	 */
	public static function instance() {
		if ( self::$__instance == NULL ) self::$__instance = new Debug_Bar_Extender;
		return self::$__instance;
	}

	/*
	 * Enable logging of results to php error log
	 */
	public static function use_error_log() {
		Debug_Bar_Extender::instance()->use_error_log = true;
	}
	
	/*
	 * Call this function once from your functions.php to prepare the debug menu
	 */
	public function prepare_debug_menu() {
		if ( 1 == $this->settings['enable_profiler'] )
			add_filter( 'debug_bar_panels', array( &$this, 'add_profiler_menu' ) );
			
		if ( 1 == $this->settings['enable_variable_lookup'] )
			add_filter( 'debug_bar_panels', array( &$this, 'add_var_menu' ) );
			
		
		add_action( 'admin_bar_menu', array( &$this, 'debug_action_admin_bar_menu' ), 101 );
		add_action( 'init', array( &$this, 'add_design_parts' ) );
		
		if ( 1 == $this->settings['track_default_vars'] || ( 1 == Debug_Bar_Extender::instance()->settings['allow_url_settings'] && isset( $_GET['dbgx_track_default_vars'] ) && 1 == $_GET['dbgx_track_default_vars'] ) ) {
			add_action( 'parse_request', array( &$this, 'trace_var_request' ), 1000 );
			add_action( 'after_setup_theme', array( &$this, 'trace_var_template' ), 1000 );
			add_action( 'posts_request', array( &$this, 'trace_var_posts_request' ), 1000 );
			//add_action( 'posts_results', array( &$this, 'trace_var_found_post_ids' ), 1000 );
			add_action( 'pre_get_posts', array( &$this, 'trace_var_wp_query' ), 1000 );
			add_action( 'wp_redirect', array( &$this, 'trace_var_redirect' ), 1000, 2 );
			add_action( 'wp_footer', array( &$this, 'trace_var_cron_array' ) );
		}
		
		if ( 1 == $this->settings['add_default_checkpoints'] || ( 1 == Debug_Bar_Extender::instance()->settings['allow_url_settings'] && isset( $_GET['dbgx_add_default_checkpoints'] ) && 1 == $_GET['dbgx_add_default_checkpoints'] ) ) {
			
			$checkpoint_actions = apply_filters( 'debugbarextender_default_checkpoint_actions', array( 
											'widgets_init',
											'register_sidebar',
											'wp_register_sidebar_widget',
											'wp_loaded',
											'parse_request',
											'send_headers',
											'parse_query',
											'pre_get_posts',
											'posts_selection',
											'wp',
											'template_redirect',
											'get_header',
											'wp_head',
											'wp_enqueue_scripts',
											'wp_print_styles',
											'wp_print_scripts',
											'get_template_part_loop',
											'loop_start',
											'the_post',
											'loop_end',
											'get_sidebar',
											'dynamic_sidebar',
											'get_search_form',
											'wp_meta',
											'get_footer',
											'twentyten_credits',
											'wp_footer',
											'wp_print_footer_scripts',
											'shutdown',
											'auth_redirect',
											'wp_default_scripts',
											'_admin_menu',
											'admin_menu',
											'admin_init',
											'load-edit.php',
											'admin_xml_ns',
											'wp_default_styles',
											'admin_enqueue_scripts',
											'admin_print_styles-edit.php',
											'admin_print_styles',
											'admin_print_scripts-edit.php',
											'admin_print_scripts',
											'admin_head-edit.php',
											'admin_head',
											'in_admin_header',
											'adminmenu',
											'admin_notices',
											'restrict_manage_posts',
											'the_post',
											'in_admin_footer',
											'admin_footer',
											'admin_print_footer_scripts',
											'admin_footer-edit.php',
									) );

			foreach( $checkpoint_actions as $action_hook ) {
				add_action( $action_hook, create_function( '$in=NULL', 'dbgx_checkpoint("' . $action_hook . ' action");if ( $in ) return $in;' ) );
			}
		}
	}

	/*
	 * Receive start time
	 */
	public function get_start_time() {
		return $this->start_time;
	}
	
	/* 
	 * Receive end time
	 */
	public function get_time() {
		return $this->current_time;
	}
	
	/* 
	 * Enqueue Styles and Scripts
	 */
	public function add_design_parts() {
		wp_enqueue_style( 'debug-bar-extender', plugins_url( "css/debug-bar-extender.css", __FILE__ ), array('debug-bar'), '20110302' );
		wp_enqueue_script( 'debug-bar-extender', plugins_url( "js/debug-bar-extender.js", __FILE__ ), array('debug-bar'), '20110302', true );
	}
	
	/*
	 * This function starts a profiling block. Currently you can have only one
	 */
	public function start( $note='' ) {
		$this->profile_data = array();

		$this->start_time = $this->current_time = microtime( true );
		$this->previous_time = $this->start_time;

		$this->log();
	}

	/*
	 * This function ends the profiling block
	 */
	public function end( $note='' ) {
		$this->end_time = $this->current_time = microtime( true );
		$this->elapsed_time = $this->current_time - $this->previous_time;
		$this->previous_time = $this->end_time;

		$this->log();
	}

	/*
	 * Add a checkpoint somewhere in your code
	 */
	public function checkpoint( $note='' ) {			
		$this->current_time = microtime( true );

		$this->elapsed_time = $this->current_time - $this->previous_time;

		$this->previous_time = $this->current_time;

		$this->log();
	}
	
	/*
	 * Trace the content of a variable
	 */
	public function trace_var( $value, $var_name = false ) {
		if ( is_object( $value ) )
			$value_cp = clone $value;
		else
			$value_cp = $value;

		$mem_usage = $this->guess_variable_size( $value );
		$trace = debug_backtrace();
		if ( !empty( $trace[1]['function'] ) && 'dbgx_trace_var' == $trace[1]['function'] )	// usage of dbgx_trace_var() shortcut function
			array_shift( $trace );
			
		$result = array(
			'file' => $trace[0]['file'],
			'line' => $trace[0]['line'],
			'function' => ( !empty( $trace[1]['function'] ) ) ? $trace[1]['function'] . '()' : '',
			'args' => ( !empty( $trace[1]['args'] ) ) ? $trace[1]['args'] : '',
			'caller_function' => ( !empty( $trace[2]['function'] ) ) ? $trace[2]['function'] . '()' : '',
			'caller_line' => ( !empty( $trace[1]['line'] ) ) ? $trace[1]['line'] : '',
			'caller_file' => ( !empty( $trace[1]['file'] ) ) ? $trace[1]['file'] : '',
			'value' => $value_cp,
			'mem_usage' => $mem_usage,
		);

		if ( false === $var_name ) {
			$var_name = 'n/a';
			if ( file_exists( $result['file'] ) && is_readable( $result['file'] ) ) {
				$line = $this->read_line_of_file( $result['file'], $result['line'] );
				if ( $line && preg_match( "/trace_var\(([^\)]+)\)/", $line, $matches ) ) {
					$var_name = trim( $matches[1] );
				}
			}
		}
		$result['var_name'] = $var_name;
		
		$this->variable_lookup[$var_name][] = $result;
		if ( isset( $this->variable_lookup[$var_name]['total_mem'] ) )
			$this->variable_lookup[$var_name]['total_mem']+=$mem_usage;
		else 
			$this->variable_lookup[$var_name]['total_mem']=$mem_usage;
		$this->log_result( $result );
	}


	/*
	 * Only internal functions below
	 */

	private function log_result( $result ) {
		if ( ! $this->use_error_log )
			return;
		
		error_log( $result['function'] . ' - ' . $result['file'] . '::L' . $result['line'] );
		error_log( $result['caller_function'] . ' - ' . $result['caller_file'] . '::L' . $result['caller_line'] );
		
		unset( $result['function'] );
		unset( $result['file'] );
		unset( $result['line'] );
		unset( $result['caller_function'] );
		unset( $result['caller_file'] );
		unset( $result['caller_line'] );
		
		error_log( print_r( $result, true ) );
	}
	
	public function trace_var_cron_array() {
		$crons = _get_cron_array();
		$this->trace_var( $crons, 'cron array via _get_cron_array()' );
	}
	
	public function trace_var_redirect( $location, $status = 301 ) {
		$this->trace_var( $location, 'wp_redirect ( ' . $location . ',' . $status . ' )' );
		return $location;
	}

	public function trace_var_request( &$request ) {
		$this->trace_var( $request, '$request via parse_request' );
		return $request;
	}

	public function trace_var_template() {
		$this->trace_var( get_template(), 'template via get_template' );
	}

	public function trace_var_posts_request( $posts_request ) {
		$this->trace_var( $posts_request, '$posts_request via posts_request' );
		return $posts_request;
	}

	public function trace_var_found_post_ids( $posts_results ) {
		foreach ( $posts_results as $post )
			$this->trace_var( $post->ID, 'found $post->IDs via posts_results' );
		return $posts_results;
	}

	public function trace_var_wp_query( $wp_query ) {
		$this->trace_var( $wp_query->query_vars, '$wp_query->query_vars via pre_get_posts' );
		return $wp_query;
	}

	private function guess_variable_size( $var ) {
		$mem = strlen( serialize( $var ) );
		return $mem;
	}

	private function read_line_of_file( $file_name, $line_number, $delimiter = "\n" ) {
		$l = 1;
		$fp = fopen( $file_name, 'r' );
		while ( !feof( $fp ) ) {
			$line = stream_get_line( $fp, 1024, $delimiter );
			if ( $l == $line_number ) {
				fclose( $fp );
				return $line;
			}
			$l++;
			$line = '';
		}
		fclose( $fp );
		return false;
	}

	public function add_profiler_menu( $panels ) {
		require_once( 'class-debug-bar-extender.php' );
		eval ( 'class Debug_Bar_Extender_Profiler extends Debug_Bar_Extender_Panel {}' );
		
		$panel = new Debug_Bar_Extender_Profiler;
		$panel->set_tab( 'Profiler', array( &$this, 'print_result_html' ) );
		$panels[] = $panel;
		return $panels;
	}

	public function add_var_menu( $panels ) {
		require_once( 'class-debug-bar-extender.php' );
		eval ( 'class Debug_Bar_Extender_Variables extends Debug_Bar_Extender_Panel {}' );
		
		$panel = new Debug_Bar_Extender_Variables;
		$panel->set_tab( 'Variable Lookup', array( &$this, 'print_variable_html' ) );
		$panels[] = $panel;
		return $panels;
	}

	public function print_result_html() {
		if ( isset( $this->profile_data['summary'] ) ) {
			$summary_data = $this->profile_data['summary'];

			$summary_data = $this->sort_summary( $summary_data, 'total_time', 'DESC' );

			$flow_data = $this->profile_data['log'];
			$total_time = $flow_data[ count( $flow_data )-1 ]['time'] + $flow_data[ count( $flow_data )-1 ]['elapsed_time'];

			// display summary
			$out = '<p style="clear:both"><strong>Summary</strong></p>';
			$out .= '<ol>';
			$counter = 1;

			foreach ( $summary_data as $line_data ) {
				$query = '<strong>' . ( ( !empty( $line_data['note'] ) ) ? $line_data['note'] . ' - ' : '' ) . $line_data['function'] . '</strong> in ' . $line_data['file'] . '::L' . $line_data['line'] . ( ( !empty( $args ) ) ? ' args : ' . serialize( $args ) : '' ) . "\n";
				$debug = 'called from <strong>' . $line_data['caller_function'] . '</strong> in ' . $line_data['caller_file'] . '::L' . $line_data['caller_line'] . "\n";
				$out .= "<li>$query<br/><div class='qdebug'>$debug <span style='float:right;'>#{$counter} ( total " . number_format( sprintf( '%0.2f', $line_data['total_time'] * 1000 ), 2, '.', ',' ) . "ms in " . $line_data['call_count']  . " calls spent)</span></div></li>\n";
				$counter++;
			}
			$out .= '</ol>';

			// display flow
			if ( count( $flow_data ) < 100 ) {
				$out .= '<p style="clear:both"><strong>Flow</strong></p>';
				$out .= '<ol>';
				$counter = 1;

				foreach ( $flow_data as $line_data ) {
					$query = '<strong>' . ( ( !empty( $line_data['note'] ) ) ? $line_data['note'] . ' - ' : '' ) . $line_data['function'] . '</strong> in ' . $line_data['file'] . '::L' . $line_data['line'] . ( ( !empty( $args ) ) ? ' args : ' . serialize( $args ) : '' ) . "\n";
					$debug = 'called from <strong>' . $line_data['caller_function'] . '</strong> in ' . $line_data['caller_file'] . '::L' . $line_data['caller_line'] . "\n";
					$out .= "<li>$query<br/><div class='qdebug'>$debug <span style='float:right;'>#{$counter} ( " . number_format( sprintf( '%0.2f', $line_data['elapsed_time'] * 1000 ), 2, '.', ',' ) . "ms @ " . number_format( sprintf( '%0.2f', $line_data['time'] * 1000 ), 2, '.', ',' ) . "ms )</span></div></li>\n";
					$counter++;
				}
				$out .= '</ol>';
			} else {
				$out .= '<p style="clear:both"><strong>Flow data is hidden as it contains too much data</strong></p>';
			}
			$query_time = '<h2><span>Total execution time:</span>' . number_format( sprintf( '%0.2f', $total_time * 1000 ), 2 ) . "ms</h2>\n";
		} else {
			$out = '<p><strong>No profiling data found</strong></p>';
			$query_time = '';
		}

		$note = '<span class="debug-note">Note: you can measure runtimes between checkpoints via <code>Debug_Bar_Extender::instance()->start( $note="" )</code>, <code>Debug_Bar_Extender::instance()->checkpoint( $note="" )</code>,  <code>Debug_Bar_Extender::instance()->end( $note="" )</code> or using the simple shortcut function <code>dbgx_checkpoint( $note="" )</code></span>';
		$out = $query_time . $out . $note;
		return $out;

	}

	public function print_variable_html( $scope='variable' ) {
		$out = "<ul class='debug-menu-links'>\n";
		$current = ' class="current"';
		$units = array( 'b', 'kb', 'mb', 'gb', 'tb', 'pb' );

		$overall_mem = $total_ops = 0;
		
		foreach ( $this->{$scope .'_lookup'} as $var => $values ) {
			$total_mem_usage = $values['total_mem'];
			$overall_mem += $total_mem_usage;
			$total_mem_usage = @number_format( $total_mem_usage / pow( 1024, ( $i = floor( log( $total_mem_usage, 1024 ) ) ) ), 2 ) . ' '.$units[$i];
			unset( $this->{$scope .'_lookup'}[$var]['total_mem'] );
			$var_ops = count( $this->{$scope .'_lookup'}[$var] );
			$total_ops += $var_ops;
			$var_size = $total_mem_usage;
			$var_title = "{$var}[$var_ops] ($var_size)";
			$var_key = md5( $var );
			$out .= "\t<li$current><a id='$scope-lookup-menu-link-$var_key' href='#$scope-lookup-menu-target-$var_key' onclick='try { return clickDebugBarExtenderLink( \"$scope-lookup-menu-targets\", this ); } catch (e) { return true; }'>$var_title</a></li>\n";
			$current = '';
		}
		$out .= "</ul>\n";

		$out .= "<div id='$scope-lookup-menu-targets'>\n";
		$current = ' style="display: block"';
		foreach ( $this->{$scope .'_lookup'} as $var => $data_lines ) {
			$first_time = true;
			$var_key = md5( $var );
			$out .= "<div id='$scope-lookup-menu-target-$var_key' class='$scope-lookup-menu-target debug-menu-target'$current>\n";
			foreach ( $data_lines as $key => $values ) {
				if ( $first_time ) {
					$out .= 'called by <strong>' . $values['function'] . '</strong> in ' . $values['file'] . '::L' . $values['line'] . ( ( !empty( $args ) ) ? ' args : ' . serialize( $args ) : '' ) . "\n";
					if ( !empty( $values['caller_line'] ) )
						$out .= 'initiated from <strong>' . $values['caller_function'] . '</strong> in ' . $values['caller_file'] . '::L' . $values['caller_line'] . "\n";
					$out .= '<br/>';
				}

				if ( 'memcache' == $scope ) {
					$prev_value = NULL;
					$diff = array();
					foreach ( $this->dcs as $dc ) {
						$diff[ md5( serialize( $values['value'][$dc] ) ) ][] = $dc;
					}
					foreach ( $diff as $dcs ) {
						if ( count( $diff ) > 1 )
							$display_key = join( ',', $dcs ) . " - $key";
						else
							$display_key = $key;

						$mem_usage = $values['mem_usage'][$dc];
						if ( $mem_usage > 100000 )
							$style = 'display:none;';
						else
							$style = 'display:block;';
						$mem_usage = number_format( $mem_usage / pow( 1024, ( $i = floor( log( $mem_usage, 1024 ) ) ) ), 2 ) . ' '.$units[$i];
						$out .= "$display_key ( $mem_usage )";
						$out .= "<pre id='$scope-lookup-menu-target-value-$var_key-" . join( '-', $dcs ) . "' style='$style'>";
						$out .= "$var = " . htmlentities( var_export( $values['value'][array_shift( $dcs )], true ) );
						$out .= "</pre>\n";
					}
				} else {
					$mem_usage = $values['mem_usage'];
					if ( $mem_usage > 100000 )
						$style = 'display:none;';
					else
						$style = 'display:block;';
					$mem_usage = number_format( $mem_usage / pow( 1024, ( $i = floor( log( $mem_usage, 1024 ) ) ) ), 2 ) . ' '.$units[$i];
					$out .= "$key ( $mem_usage )";
					$out .= "<pre id='$scope-lookup-menu-target-value-$var_key' style='$style'>";
					$out .= "$var = " . htmlentities( var_export( $values['value'], true ) );
					$out .= "</pre>\n";
				}
				$first_time = false;

			}
			$current = '';
			$out .= "</div>";
		}

		if ( $overall_mem > 0 )
			$overall_mem = @number_format( $overall_mem / pow( 1024, ( $i = floor( log( $overall_mem, 1024 ) ) ) ), 2 ) . ' '.$units[$i];
		else
			$overall_mem = 0;
			
		$header = "<div id='$scope-lookup'>\n";
		$header .= "<h2><span>Total mem:</span>". $overall_mem ."</h2>";

		$out .= "</div>";

		if ( 'variable' == $scope )
			$note = '<span class="debug-note">Note: you can screen additional variables via <code>Debug_Bar_Extender::instance()->trace_var( $value, $var_name = false )</code> or simply by using <code>dbgx_trace_var( $value, $var_name = false )</code>. Omitting the <code>$var_name</code> will let the script look it up. Please be aware that sizes are approximate based on <code>strlen(serialize())</code></span>';
		elseif ( 'memcache' == $scope )
			$note = '<span class="debug-note">Note: you can screen additional keys via <code>Debug_Bar_Extender::instance()->trace_memcache( $key, $group = "default" )</code>. This lookups are gathering the values from all datacenters and will show one value if all results are the same and multiple if the results in the datacenters differ. As sandboxes don\'t update remote memcache servers make sure to prime the caches by a live page load prior to sandboxing a site.</span>';
		return $header.$out.$note.'</div>';
	}

	private function log() {
		$trace = debug_backtrace();
		if ( !empty( $trace[2]['function'] ) && 'dbgx_checkpoint' == $trace[2]['function'] )	// usage of dbgx_checkpoint() shortcut function
			array_shift( $trace );
			
		$caller = $trace[1]; // it always goes through start, checkpoint or end
		$result = array(
			'file' => $trace[1]['file'],
			'line' => $trace[1]['line'],
			'function' => ( !empty( $trace[2]['function'] ) ) ? $trace[2]['function'] . '()' : '',
			'args' => ( !empty( $trace[2]['args'] ) ) ? $trace[2]['args'] : '',
			'caller_function' => ( !empty( $trace[3]['function'] ) ) ? $trace[3]['function'] . '()' : '',
			'caller_line' => ( !empty( $trace[2]['line'] ) ) ? $trace[2]['line'] : '',
			'caller_file' => ( !empty( $trace[2]['file'] ) ) ? $trace[2]['file'] : '',

		);
		$array_key = md5( serialize( array( $result['file'], $result['line'], $result['function'], $result['args'] ) ) );

		$substractor = $this->start_time;
		$time_line = array( 'time' => ( $this->current_time ) - $substractor,
			'elapsed_time' => ( $this->elapsed_time ),
		);
		$profile_data = array_merge( $time_line, $result );

		$profile_data['note'] = $trace[1]['args'][0];

		$this->profile_data['log'][] = $profile_data;
		if ( isset( $this->profile_data['summary'][$array_key]['call_count'] ) ) {
			$this->profile_data['summary'][$array_key]['call_count']++;
			$this->profile_data['summary'][$array_key]['total_time'] += $profile_data['elapsed_time'];
		} else {
			$profile_data['call_count'] = 1;
			$profile_data['total_time'] = $profile_data['elapsed_time'];
			unset( $profile_data['elapsed_time'] );
			unset( $profile_data['time'] );
			$this->profile_data['summary'][$array_key] = $profile_data;
		}
		
		$this->log_result( $profile_data );
		return $profile_data;
	}

	private function sort_summary( $data, $sortby_index, $order = 'ASC' ) {
		$_tmp = $sorted = array();
		foreach ( $data as $key => $values ) {
			if ( !is_array( $values[$sortby_index] ) )
				$_tmp[$key] = $values[$sortby_index];
		}

		natsort( $_tmp );

		if ( 'DESC' == strtoupper( $order ) )
			$_tmp = array_reverse( $_tmp, true );

		foreach ( $_tmp as $key => $vals ) {
			$sorted[$key] = $data[$key];
		}
		return $sorted;
	}
	
	public function register_settings_page() {
		add_options_page( 'Debug Bar Extender Settings', 'Debug Bar Extender', 'manage_options', 'debug-bar-extender', array( &$this, 'settings_page' ) );
	}


	public function register_setting() {
		register_setting( 'debugbarextender_settings', 'debugbarextender_settings', array( &$this, 'validate_settings') );
	}
	
	public function validate_settings( $settings ) {
		if ( !empty( $_POST['debugbarextender-defaults'] ) ) {
			$settings = $this->default_settings;
			$_REQUEST['_wp_http_referer'] = add_query_arg( 'defaults', 'true', $_REQUEST['_wp_http_referer'] );
		} else {
			//foreach( $settings as $setting => $value ) 
				//$settings[$setting] = (int) ( !empty( $settings[$setting] ) ) ? $settings[$setting] :$this->default_settings[$setting];
		}

		return $settings;
	}
	
	public function settings_page() {
		if ( !current_user_can( 'manage_options' ) ) {
			global $current_user;
			$msg = "I'm sorry, " . $current_user->display_name . " I'm afraid I can't do that.";
			echo '<div class="wrap">' . $msg . '</div>';
			return false;
		}
	?>
	<div class="wrap">
	<?php if ( function_exists('screen_icon') ) screen_icon(); ?>
		<h2>Debug Bar Extender Settings</h2>
	
		<form method="post" action="options.php">
	
		<?php settings_fields( 'debugbarextender_settings' ); ?>
	
		<table class="form-table">
			<?php foreach( $this->settings as $setting => $value): ?>
			<tr valign="top">
				<th scope="row"><label for="debugbarextender-<?php echo $setting; ?>"><?php if ( isset( $this->settings_texts[$setting]['label'] ) ) { echo $this->settings_texts[$setting]['label']; } else { echo $setting; } ?></label></th>
				<td>
					<select name="debugbarextender_settings[<?php echo $setting; ?>]" id="debugbarextender-<?php echo $setting; ?>" class="postform">
						<?php 
							$yesno = array( 0 => 'No', 1 => 'Yes' ); 
							foreach ( $yesno as $val => $txt ) {
								echo '<option value="' . esc_attr( $val ) . '"' . selected( $value, $val, false ) . '>' . esc_html( $txt ) . "&nbsp;</option>\n";
							}
						?>
					</select><br />
					<?php if ( !empty( $this->settings_texts[$setting]['desc'] ) ) { echo $this->settings_texts[$setting]['desc']; } ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
		
		<p class="submit">
	<?php
			if ( function_exists( 'submit_button' ) ) {
				submit_button( null, 'primary', 'debugbarextender-submit', false );
				echo ' ';
				submit_button( 'Reset to Defaults', 'primary', 'debugbarextender-defaults', false );
			} else {
				echo '<input type="submit" name="debugbarextender-submit" class="button-primary" value="Save Changes" />' . "\n";
				echo '<input type="submit" name="debugbarextender-defaults" id="debugbarextender-defaults" class="button-primary" value="Reset to Defaults" />' . "\n";
			}
	?>
		</p>
	
		</form>
	</div>
	
	<?php
	}
	
	public function debug_action_admin_bar_menu() {
		global $wp_admin_bar;
		if ( 0 == Debug_Bar_Extender::instance()->settings['allow_url_settings'] || 0 == Debug_Bar_Extender::instance()->settings['enable_admin_bar_menu'] )
			return;
			
		$wp_admin_bar->add_menu( array(
			'id' => 'dbg',
			'title' => 'DbgBExt',
			'href' => false,
		) );
		$wp_admin_bar->add_menu( array(
			'parent' => 'dbg',
			'title' => 'default vars',
			'href' => add_query_arg( array( 'dbgx_use_error_log' => 0, 'dbgx_add_default_checkpoints' => 0, 'dbgx_track_default_vars' => 1 ) ),
		) );
		$wp_admin_bar->add_menu( array(
			'parent' => 'dbg',
			'title' => 'default checkpoints',
			'href' => add_query_arg( array( 'dbgx_use_error_log' => 0, 'dbgx_add_default_checkpoints' => 1, 'dbgx_track_default_vars' => 0 ) ),
		) );
		$wp_admin_bar->add_menu( array(
			'parent' => 'dbg',
			'title' => 'default checkpoints+vars',
			'href' => add_query_arg( array( 'dbgx_use_error_log' => 0, 'dbgx_add_default_checkpoints' => 1, 'dbgx_track_default_vars' => 1 ) ),
		) );
		$wp_admin_bar->add_menu( array(
			'parent' => 'dbg',
			'title' => 'send to error_log',
			'href' => add_query_arg( array( 'dbgx_use_error_log' => 1, 'dbgx_add_default_checkpoints' => 0, 'dbgx_track_default_vars' => 0 ) ),
		) );
		$wp_admin_bar->add_menu( array(
			'parent' => 'dbg',
			'title' => 'default vars -> log',
			'href' => add_query_arg( array( 'dbgx_use_error_log' => 1, 'dbgx_add_default_checkpoints' => 0, 'dbgx_track_default_vars' => 1 ) ),
		) );
		$wp_admin_bar->add_menu( array(
			'parent' => 'dbg',
			'title' => 'default checkpoints -> log',
			'href' => add_query_arg( array( 'dbgx_use_error_log' => 1, 'dbgx_add_default_checkpoints' => 1, 'dbgx_track_default_vars' => 0 ) ),
		) );
		$wp_admin_bar->add_menu( array(
			'parent' => 'dbg',
			'title' => 'default checkpoints+vars -> log',
			'href' => add_query_arg( array( 'dbgx_use_error_log' => 1, 'dbgx_add_default_checkpoints' => 1, 'dbgx_track_default_vars' => 1 ) ),
		) );
	}


}

Debug_Bar_Extender::init();

if ( !function_exists( 'dbgx_checkpoint' ) ) {
	function dbgx_checkpoint( $note = '' ) {
		if ( 0 == Debug_Bar_Extender::instance()->get_start_time() ) {
			Debug_Bar_Extender::instance()->start( $note );
		} else {
			Debug_Bar_Extender::instance()->checkpoint( $note );
		}
	}
}

if ( !function_exists( 'dbgx_trace_var' ) ) {
	function dbgx_trace_var( $value, $var_name = false ) {
		Debug_Bar_Extender::instance()->trace_var( $value, $var_name );
	}
}
