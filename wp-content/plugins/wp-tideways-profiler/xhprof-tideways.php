<?php
/*
Plugin Name: Tideways Profiler
Description: Adds PHP profiling support to your Wordpress using the Tideways fork of Facebook's XHProf Profiler.
Version: 1.0
Author: Alberto Varela
Author URI: http://www.berriart.com
License: GPL2
*/
/*  Copyright 2012  Alberto Varela  (email : alberto@berriart.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

    The license is also available at http://www.gnu.org/licenses/gpl-2.0.html
*/

/*** FUNCTIONS ***/

// Check for xhprof support
function berriart_xhprof_profiler_xhprof_is_enabled() {
    return extension_loaded('tideways');
}

// Check for xhprof support and debug enabled
function berriart_xhprof_profiler_xhprof_and_debug_is_enabled() {
    $enabled = false;
    if(defined('WP_DEBUG') && WP_DEBUG == true &&
       defined( 'SQUARE1_XHPROF' ) && SQUARE1_XHPROF &&
       berriart_xhprof_profiler_xhprof_is_enabled()) {
        $enabled = true;
    }

    return $enabled;
}

// Starts xhprof profiling
function berriart_xhprof_profiler_enable_xhprof() {
    if(berriart_xhprof_profiler_xhprof_and_debug_is_enabled()) {
        // Ensure xhprof util libraries are included
        if(!function_exists('xhprof_error'))
            include 'facebook-xhprof/xhprof_lib/utils/xhprof_lib.php';
        if(!class_exists('XHProfRuns_Default'))
            include 'facebook-xhprof/xhprof_lib/utils/xhprof_runs.php';
        // Enable xhprof
        tideways_enable();
    }
} 

// Ends xhprof profiling
function berriart_xhprof_profiler_disable_xhprof() {
    if(berriart_xhprof_profiler_xhprof_and_debug_is_enabled()) {
        $profiler_namespace = get_bloginfo('name');
        $xhprof_data = tideways_disable();
        $xhprof_runs = new XHProfRuns_Default( apply_filters( 'tribe/project/xhprof/directotry', '/tmp' ) );
        $run_id = $xhprof_runs->save_run($xhprof_data, $profiler_namespace);
     
        // url to the XHProf UI libraries 
        $profiler_url = plugins_url(sprintf('facebook-xhprof/xhprof_html/index.php?run=%s&source=%s', $run_id, $profiler_namespace) , __FILE__ );

	    if ( defined( 'XHPROF_FOOTER' ) && XHPROF_FOOTER ) {
		    echo '----> <a href="' . $profiler_url . '" target="_blank">Profiler output</a>';
	    }

	    if ( defined( 'XHPROF_LOG' ) && XHPROF_LOG ) {
		    error_log( 'xhprof url: ' . $profiler_url );
	    }
    }
} 

/*** HOOKS ***/

// Adding hooks on plugins_loaded action
function berriart_xhprof_profiler_muplugins_loaded() {
    berriart_xhprof_profiler_enable_xhprof();
}
add_action('plugins_loaded', 'berriart_xhprof_profiler_muplugins_loaded');

// Adding hooks on shutdown action
function berriart_xhprof_profiler_shutdown() {
    berriart_xhprof_profiler_disable_xhprof();
}
add_action('shutdown', 'berriart_xhprof_profiler_shutdown');

// Adding hook on plugin activation
function berriart_xhprof_profiler_activate() {
    if(!berriart_xhprof_profiler_xhprof_is_enabled()) {
        die('You must install xhprof and enable it on your php.ini before activating the plugin');
    }
}
register_activation_hook( __FILE__, 'berriart_xhprof_profiler_activate' );