<?php
/*
  Limit Login Attempts: plugin upgrade functions
  Version 2.0beta4

  Copyright 2008 - 2011 Johan Eenfeldt

  Licenced under the GNU GPL:

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
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/* Die if included directly (without any PHP warnings, etc) */
if (!defined('ABSPATH'))
    die();


/*
 * Options available in plugin version 1.x 
 *
 * This file is included in function context. This means that global variables
 * have to be defined with $GLOBALS.
 */
$GLOBALS['limit_login_options_v1'] =
	array('client_type', 'allowed_retries', 'lockout_duration'
	      , 'allowed_lockouts', 'long_duration', 'valid_duration', 'cookies'
	      , 'lockout_notify', 'notify_email_after');


/*
 * Functions related to plugin upgrade
 */

/* Upgrade options from previous version if necessary */
function limit_login_handle_upgrades() {
	/*
	 * Do we have new-style options?
	 */
	if (!limit_login_options_exists()) {
		/*
		 * No stored new-style options, upgrade from old style options?
		 */
		if (!limit_login_v1_options_exists()) {
			/* No -- probably new install */
			return;
		}
		
		/*
		 * Yes. Upgrade from plugin version 1.x 
		 */
		limit_login_upgrade_v1_options();
		limit_login_upgrade_array_autoloads();
		limit_login_upgrade_log();
		limit_login_upgrade_statistic('lockouts_total');
		return;
	}

	/*
	 * New-style options are versioned so we can check stored value
	 */
	$current_version = limit_login_option('version');
	if ($current_version == LIMIT_LOGIN_VERSION)
		return;

	/*
	 * Upgrade. The basic stuff (adding new options with default values, 
	 * remove obsolete values) is handled by option loading & sanitizing.
	 */
	switch ($current_version) {
	case 2:
		/* Plugin version 2.0beta1 - 2.0beta3 */
		limit_login_upgrade_array_autoloads();
		limit_login_upgrade_log();
		limit_login_upgrade_statistic('lockouts_total');
		limit_login_upgrade_statistic('reg_lockouts_total');
		break;
	}

	// Make sure no old option value remain
	limit_login_delete_old_option_values();

	/* Set current version */
	global $limit_login_options;

	$limit_login_options['version'] = LIMIT_LOGIN_VERSION;
	limit_login_update_options();
}


/*
 * Upgrade helpfunctions
 */

/* Upgrade statistics value to new statistic handling */
function limit_login_upgrade_statistic($name, $option_name=null) {
	if (is_null($option_name))
		$option_name = limit_login_name($name);
	$value = get_option($option_name);
	if ($value !== false)
		delete_option($option_name);
	if (is_numeric($value) && $value > 0)
		limit_login_statistic_set($name, $value, true);
}


/* Make sure all stored arrays have the right autoload */
function limit_login_upgrade_array_autoloads() {
	$arrays = array('lockouts', 'retries', 'retries_valid'
			, 'registrations', 'registrations_valid'
			, 'logged');

	/*
	 * For every existing entry we delete it from the options table and
	 * recreate (with correct autoload)
	 */
	foreach ($arrays as $array_name) {
		$real_array_name = limit_login_name($array_name);
		$current_value = get_option($real_array_name);

		if ($current_value === false)
			continue;

		delete_option($real_array_name);
		limit_login_store_array($array_name, $current_value);
	}
}


/* Upgrade IP log to new format */
function limit_login_upgrade_log() {
	$log = limit_login_get_array('logged');

	if (!is_array($log) || count($log) <= 0)
		return;

	$new_log = array();
	foreach ($log as $ip => $iplog) {
		$new_log[$ip] = array();
		$new_log[$ip][0] = 0;
		$new_log[$ip][1] = $iplog;
	}

	limit_login_store_array('logged', $new_log);
}


/*
 * Plugin options v1 => current version
 */

/* Check if v1 style options exists */
function limit_login_v1_options_exists() {
	global $limit_login_options_v1;

	foreach ($limit_login_options_v1 as $name) {
		$a = get_option(limit_login_name($name));

		if ($a !== false)
			return true;
	}

	return false;
}


/* Get stored v1 style options */
function limit_login_fetch_v1_options() {
	global $limit_login_options_v1;

	$options = array();

	foreach ($limit_login_options_v1 as $name) {
		$a = get_option(limit_login_name($name));

		if ($a === false)
			continue;

		$options[$name] = $a;
	}

	return $options;
}


/*
 * Upgrade from old v1 style options (and store modified options)
 * 
 * Note that startup will have populated $limit_login_options with default
 * values.
 */
function limit_login_upgrade_v1_options() {
	global $limit_login_options;

	$old_options = limit_login_fetch_v1_options();
	if (empty($old_options))
		return;

	foreach($limit_login_options AS $name => $value) {
		if (!isset($old_options[$name]))
			continue;
		$limit_login_options[$name] = $old_options[$name];
	}

	limit_login_sanitize_options();
	limit_login_update_options();
	limit_login_delete_old_option_values();
}


/* Delete any old stored options */
function limit_login_delete_old_option_values() {
	/*
	 * Currently this is the old style v1 options. But there might be more
	 * in the future.
	 */

	global $limit_login_options_v1;

	foreach ($limit_login_options_v1 as $name) {
		$option_name = limit_login_name($name);
		if (get_option($option_name) !== false)
			delete_option($option_name);
	}
}
?>