<?php  if ( ! defined( 'ABSPATH' ) ) exit;
/*
Plugin Name: Debug Bar Action Hooks
Plugin URI: http://wordpress.org/extend/plugins/debug-bar-action-hooks/
Description: Displays a list of actions fired for the current request. Requires the debug bar plugin.
Author: ampt
Version: 0.0.1
*/

if ( ! function_exists( 'debug_bar_actions_panel' ) ) {
    function debug_bar_actions_panel( $panels ) {
        require_once 'class-debug-bar-actions.php';
        $panels[] = new Debug_Bar_Actions();
        return $panels;
    }
}
add_filter( 'debug_bar_panels', 'debug_bar_actions_panel' );

if ( ! function_exists( 'debug_bar_actions_output_cb' ) ) {
    function debug_bar_actions_output_cb( $buffer ) {
        global $wp_actions;

        $total = sprintf( '<h2><span>Total Actions:</span>%d</h2>', count( $wp_actions ) );
        $out = '<div id="action-hooks-list">'.$total.'<ol style="clear: left;">';

        foreach ( $wp_actions as $key => $val ) {
            $out .= "<li>{$key}</li>";
        }

        $out .= '</ol></div>';

        return str_replace( '{debug_bar_actions_hooks}', $out, $buffer );
    }
}

// Start the output buffer with our callback so we can show all actions fired
// for the current request, including the shutdown action.
ob_start( 'debug_bar_actions_output_cb' );
