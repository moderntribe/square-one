<?php
/*
  Limit Login Attempts: registration enforcement functions
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
 * Functions start here
 */

/* Check if it is ok to register new user */
function is_limit_login_reg_ok() {
	if (!limit_login_option('register_enforce'))
		return true;

	$ip = limit_login_get_address();

	/* not too many (valid) registrations? */
	$valid = limit_login_get_array('registrations_valid');
	$regs = limit_login_get_array('registrations');
	$allowed = limit_login_option('register_allowed');
	return (!isset($regs[$ip]) || $regs[$ip] < $allowed
		|| !limit_login_check_ip($valid, $ip));
}


/* Clean up any old registration attempts and save arrays */
function limit_login_cleanup_registrations() {
	$valid = limit_login_get_array('registrations_valid');
	$regs = limit_login_get_array('registrations');

	if (empty($valid) && empty($regs))
		return;

	$changed = false;
	foreach ($valid as $ip => $until) {
		if ($until < $now) {
			unset($valid[$ip]);
			unset($regs[$ip]);
			$changed = true;
		}
	}

	/* go through registrations directly, if for some reason they've gone out of sync */
	foreach ($regs as $ip => $reg) {
		if (!isset($valid[$ip])) {
			unset($regs[$ip]);
			$changed = true;
		}
	}

	if ($changed) {
		limit_login_store_array('registrations_valid', $valid);
		limit_login_store_array('registrations', $regs);
	}
}


/*
 * Handle bookkeeping when new user is registered
 *
 * Increase nr of registrations and reset valid value.
 */
function limit_login_reg_add() {
	if (!limit_login_option('register_enforce'))
		return;

	$ip = limit_login_get_address();

	/* Get the arrays with registrations and valid information */
	$regs = limit_login_get_array('registrations');
	$valid = limit_login_get_array('registrations_valid');

	/* Check validity and add one registration */
	if (isset($regs[$ip]) && isset($valid[$ip]) && time() < $valid[$ip])
		$regs[$ip] ++;
	else
		$regs[$ip] = 1;
	$valid[$ip] = time() + limit_login_option('register_duration');

	limit_login_store_array('registrations', $regs);
	limit_login_store_array('registrations_valid', $valid);

	/* registration lockout? increase statistics */
	if ($regs[$ip] >= limit_login_option('register_allowed'))
		limit_login_statistic_inc('reg_lockouts_total');

	/* do housecleaning */
	limit_login_cleanup();
}


/* 
 * Filter: check if new registration is allowed, and filter error messages
 * to remove possibility to brute force user login
 */
function limit_login_filter_registration($errors) {
	global $limit_login_my_error_shown;

	$limit_login_my_error_shown = true;

	if (!is_limit_login_reg_ok()) {
		$errors = new WP_Error();
		$errors->add('lockout', limit_login_reg_error_msg());
		return $errors;
	}

	/*
	 * Not locked out. Now enforce error msg filter and count attempt if there
	 * are no errors.
	 */

	if (!is_wp_error($errors)) {
		limit_login_reg_add();
		return $errors;
	}

	/*
	 * If more than one error message (meaning both login and email was
	 * invalid) we strip any 'username_exists' message.
	 *
	 * This is to stop someone from trying different usernames with a known
	 * bad / empty email address.
	 */

	$codes = $errors->get_error_codes();
	if (count($codes) <= 1) {
		if (count($codes) == 0)
			limit_login_reg_add();

		return $errors;
	}

	$key = array_search('username_exists', $codes);

	if ($key !== false) {
		unset($codes[$key]);

		$old_errors = $errors;
		$errors = new WP_Error();
		foreach ($codes as $key => $code)
			$errors->add($code, $old_errors->get_error_message($code));
	}

	return $errors;
}


/* Construct message for registration lockout */
function limit_login_reg_error_msg() {
	$msg = __('<strong>ERROR</strong>: Too many new user registrations.', 'limit-login-attempts') . ' ';
	return limit_login_error_msg('registrations_valid', $msg);
}


/* Filter: remove other registration error messages */
function limit_login_reg_filter_login_message($content) {
	if (is_limit_login_reg_page() && !is_limit_login_reg_ok())
		return '';

	return $content;
}


/* Should we show errors and messages on this page? */
function is_limit_login_reg_page() {
	if (isset($_GET['key'])) {
		/* reset password */
		return false;
	}

	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

	return ( $action == 'register' );
}


/* Add a message to login page when necessary */
function limit_login_add_reg_error_message() {
	global $error, $limit_login_my_error_shown;

	if (is_limit_login_reg_page() && !is_limit_login_reg_ok()
	    && !$limit_login_my_error_shown) {
		$error = limit_login_reg_error_msg();
		$limit_login_my_error_shown = true;
	}
}
?>