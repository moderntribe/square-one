<?php
/*
  Plugin Name: Limit Login Attempts
  Plugin URI: http://devel.kostdoktorn.se/limit-login-attempts
  Description: Limit rate of login attempts, including by way of cookies, for each IP.
  Author: Johan Eenfeldt
  Author URI: http://devel.kostdoktorn.se
  Text Domain: limit-login-attempts
  Version: 2.0beta4

  Copyright 2008 - 2011 Johan Eenfeldt

  Thanks to Michael Skerwiderski for reverse proxy handling suggestions.

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

/*
 * Plugin TODO list
 *
 * Now:
 * - test with/without registration enforce enabled
 *
 * Future:
 * - add logging of registration lockouts
 * - add user_meta with IP when registering users to allow trace
 * - track when last login, IP of last few successful logins
 */

/* Die if included directly (without any PHP warnings, etc) */
if (!defined('ABSPATH'))
	die();

/*
 * Constants
 */

/* Different ways to get remote address: direct & behind proxy */
define('LIMIT_LOGIN_DIRECT_ADDR', 'REMOTE_ADDR');
define('LIMIT_LOGIN_PROXY_ADDR', 'HTTP_X_FORWARDED_FOR');

/* Notify value checked against these in limit_login_sanitize_options() */
define('LIMIT_LOGIN_LOCKOUT_NOTIFY_ALLOWED', 'log,email');

/*
 * Variables
 *
 * Also check options file for option variables.
 */

$limit_login_my_error_shown = false; /* have we shown our stuff? */
$limit_login_just_lockedout = false; /* locked-out this pageload? */
$limit_login_nonempty_credentials = false; /* user and pwd nonempty */
$limit_login_statistics = null; /* statistics, stored in option table */

/*
 * Startup
 */

add_action('init', 'limit_login_setup');


/*
 * Functions start here
 */

/* Get options and setup filters & actions */
function limit_login_setup() {
	load_plugin_textdomain('limit-login-attempts', false
			       , plugin_dir_path(__FILE__) . 'languages');

	limit_login_require_file('options');
	limit_login_setup_options();

	/*
	 * Filters and actions
	 */

	add_action('wp_login_failed', 'limit_login_failed');
	add_filter('wp_authenticate_user', 'limit_login_wp_authenticate_user', 99999, 2);
	add_filter('shake_error_codes', 'limit_login_failure_shake');
	add_action('login_head', 'limit_login_add_error_message');
	add_action('login_errors', 'limit_login_fixup_error_messages');

	/* Handle auth-cookies? */
	if (limit_login_option('cookies')) {
		add_action('plugins_loaded', 'limit_login_handle_cookies', 99999);
		add_action('auth_cookie_bad_hash', 'limit_login_failed_cookie');

		global $wp_version;

		/* auth_cookie_valid action only available in WP >= 3.0 */
		if (version_compare($wp_version, '3.0', '>=')) {
			add_action('auth_cookie_bad_hash', 'limit_login_failed_cookie_hash');
			add_action('auth_cookie_valid', 'limit_login_valid_cookie', 10, 2);
		} else {
			add_action('auth_cookie_bad_hash', 'limit_login_failed_cookie');
		}
	}

	/*
	 * todo: this action should be changed to the 'authenticate' filter as
	 * it will probably be deprecated.
	 */
	add_action('wp_authenticate', 'limit_login_track_credentials', 10, 2);

	if (limit_login_option('register_enforce')) {
		limit_login_require_file('registrations');

		add_filter('registration_errors', 'limit_login_filter_registration');
		add_filter('login_message', 'limit_login_reg_filter_login_message');

		/*
		 * This filter needs to run at a lower priority than
		 * limit_login_add_error_message() (meaning it will be executed
		 * before)
		 */
		add_action('login_head', 'limit_login_add_reg_error_message', 9);
	}

	if (limit_login_option('disable_pwd_reset') || limit_login_option('disable_pwd_reset_username'))
		add_filter('allow_password_reset', 'limit_login_filter_pwd_reset', 10, 2);

	if (is_admin()) {
		limit_login_require_file('admin');

		add_action('admin_menu', 'limit_login_admin_menu');
		add_filter('plugin_action_links', 'limit_login_filter_plugin_actions', 10, 2 );
		add_action('rightnow_end', 'limit_login_dashboard_info');

		limit_login_require_file('upgrade');
		limit_login_handle_upgrades();
	}
}


/*
 * Login handling functions
 */

/* Is it ok to login? */
function is_limit_login_ok() {
	/* Test that there is not a (valid) lockout on ip in lockouts array */
	return ! limit_login_check_ip(limit_login_get_array('lockouts'));
}


/* Filter: allow login attempt? (called from wp_authenticate()) */
function limit_login_wp_authenticate_user($user, $password) {
	if (is_wp_error($user) || is_limit_login_ok())
		return $user;

	global $limit_login_my_error_shown;
	$limit_login_my_error_shown = true;

	$error = new WP_Error();
	/* This error should be the same as in "shake it" filter below */
	$error->add('too_many_retries', limit_login_error_msg());
	return $error;
}


/* Filter: add this failure to login page "Shake it!" */
function limit_login_failure_shake($error_codes) {
	$error_codes[] = 'too_many_retries';
	return $error_codes;
}


/*
 * Action: failed cookie login hash
 *
 * Make sure same invalid cookie doesn't get counted more than once.
 *
 * Requires WordPress version 3.0.0, previous versions use limit_login_failed_cookie()
 */
function limit_login_failed_cookie_hash($cookie_elements) {
	limit_login_clear_auth_cookie();

	/*
	 * Under some conditions an invalid auth cookie will be used multiple
	 * times, which results in multiple failed attempts from that one
	 * cookie.
	 *
	 * Unfortunately I've not been able to replicate this consistently and
	 * thus have not been able to make sure what the exact cause is.
	 *
	 * Probably it is because a reload of for example the admin dashboard
	 * might result in multiple requests from the browser before the invalid
	 * cookie can be cleared.
	 *
	 * Handle this by only counting the first attempt when the exact same
	 * cookie is attempted for a user.
	 */

	extract($cookie_elements, EXTR_OVERWRITE);

	// Check if cookie is for a valid user
	$user = get_userdatabylogin($username);
	if (!$user) {
		// "shouldn't happen" for this action
		limit_login_failed($username);
		return;
	}

	$previous_cookie = get_user_meta($user->ID, 'limit_login_previous_cookie', true);
	if ($previous_cookie && $previous_cookie == $cookie_elements) {
		// Identical cookies, ignore this attempt
		return;
	}

	// Store cookie
	if ($previous_cookie)
		update_user_meta($user->ID, 'limit_login_previous_cookie', $cookie_elements);
	else
		add_user_meta($user->ID, 'limit_login_previous_cookie', $cookie_elements, true);

	limit_login_failed($username);
}


/*
 * Action: successful cookie login
 *
 * Clear any stored cookie in user_meta.
 *
 * Requires WordPress version 3.0.0, not used in previous versions
 */
function limit_login_valid_cookie($cookie_elements, $user) {
	/*
	 * As all meta values get cached on user load this should not require
	 * any extra work for the common case of no stored value.
	 */

	if (get_user_meta($user->ID, 'limit_login_previous_cookie'))
		delete_user_meta($user->ID, 'limit_login_previous_cookie');
}


/*
 * Action: called in plugin_loaded (really early) to make sure we do not allow
 * auth cookies while locked out.
 */
function limit_login_handle_cookies() {
	if (is_limit_login_ok())
		return;

	limit_login_clear_auth_cookie();
}


/* Action: failed cookie login wrapper for limit_login_failed() */
function limit_login_failed_cookie($cookie_elements) {
	limit_login_clear_auth_cookie();

	limit_login_failed($cookie_elements['username']);
}


/* Make sure auth cookie really get cleared (for this session too) */
function limit_login_clear_auth_cookie() {
	wp_clear_auth_cookie();

	if (!empty($_COOKIE[AUTH_COOKIE])) {
		$_COOKIE[AUTH_COOKIE] = '';
	}
	if (!empty($_COOKIE[SECURE_AUTH_COOKIE])) {
		$_COOKIE[SECURE_AUTH_COOKIE] = '';
	}
	if (!empty($_COOKIE[LOGGED_IN_COOKIE])) {
		$_COOKIE[LOGGED_IN_COOKIE] = '';
	}
}


/*
 * Action when login attempt failed
 *
 * Increase nr of retries (if necessary). Reset valid value. Setup
 * lockout if nr of retries are above threshold. And more!
 */
function limit_login_failed($username) {
	$ip = limit_login_get_address();

	$lockouts = limit_login_get_array('lockouts');
	if (limit_login_check_ip($lockouts, $ip)) {
		/* if currently locked-out, do not add to retries */
		return;
	}

	/* Get the arrays with retries and retries-valid information */
	$retries = limit_login_get_array('retries');
	$valid = limit_login_get_array('retries_valid');

	/* Check validity and add one to retries */
	if (isset($retries[$ip]) && limit_login_check_ip($valid, $ip)) {
		$retries[$ip] ++;
	} else {
		$retries[$ip] = 1;
	}
	$valid[$ip] = time() + limit_login_option('valid_duration');

	/* lockout? */
	if($retries[$ip] % limit_login_option('allowed_retries') != 0) {
		/* 
		 * Not lockout (yet!)
		 * Do housecleaning (which also saves retry/valid values).
		 */
		limit_login_cleanup($retries, null, $valid);
		return;
	}

	/* lockout! */

	global $limit_login_just_lockedout;
	$limit_login_just_lockedout = true;

	/* setup lockout, reset retries as needed */
	$retries_long = limit_login_option('allowed_retries')
		* limit_login_option('allowed_lockouts');
	if ($retries[$ip] >= $retries_long) {
		/* long lockout, reset retries */
		$lockouts[$ip] = time() + limit_login_option('long_duration');
		unset($retries[$ip]);
		unset($valid[$ip]);
	} else {
		/* normal lockout, keep retries to count toward long lockout */
		$lockouts[$ip] = time() + limit_login_option('lockout_duration');
	}

	/* do housecleaning and save values */
	limit_login_cleanup($retries, $lockouts, $valid);

	/* do any notification */
	limit_login_notify($username);

	/* increase statistics */
	limit_login_statistic_inc('lockouts_total');
}


/* Clean up old lockouts and retries, and save supplied arrays */
function limit_login_cleanup_logins($retries = null, $lockouts = null, $valid = null) {
	$now = time();
	$lockouts = !is_null($lockouts) && is_array($lockouts) ? $lockouts : limit_login_get_array('lockouts');

	/* remove old lockouts */
	$changed = false;
	foreach ($lockouts as $ip => $lockout) {
		if ($lockout >= $now)
			continue;

		unset($lockouts[$ip]);
		$changed = true;
	}
	if ($changed)
		limit_login_store_array('lockouts', $lockouts);

	/* remove retries that are no longer valid */
	$valid = !is_null($valid) && is_array($valid) ? $valid : limit_login_get_array('retries_valid');
	$retries = !is_null($retries) && is_array($retries) ? $retries : limit_login_get_array('retries');

	if (empty($valid) && empty($retries))
		return;

	$changed = false;
	foreach ($valid as $ip => $lockout) {
		if ($lockout >= $now)
			continue;

		unset($valid[$ip]);
		unset($retries[$ip]);
		$changed = true;
	}

	/* go through retries directly, if for some reason they've gone out of sync */
	foreach ($retries as $ip => $retry) {
		if (isset($valid[$ip]))
			continue;

		unset($retries[$ip]);
		$changed = true;
	}

	if ($changed) {
		limit_login_store_array('retries', $retries);
		limit_login_store_array('retries_valid', $valid);
	}
}


/* Clean up old lockouts and retries, and save supplied arrays */
function limit_login_cleanup($retries = null, $lockouts = null, $valid = null) {
	limit_login_cleanup_logins($retries, $lockouts, $valid);

	/* While we are here, do the same for the registration arrays */
	if (limit_login_option('register_enforce'))
		limit_login_cleanup_registrations();
}


/* Check if user have level capability */
function limit_login_user_has_level($userid, $level) {
	$userid = intval($userid);
	$level = intval($level);

	if ($userid <= 0)
		return false;

	$user = new WP_User($userid);

	return ($user && $user->has_cap($level));
}


/* Filter: enforce that password reset is allowed */
function limit_login_filter_pwd_reset($b, $userid) {
	$limit = null;

	/* What limit (max privilege level) to use, if any */
	if (limit_login_option('disable_pwd_reset')) {
		/* limit on all pwd resets */
		$limit = limit_login_option('pwd_reset_limit');
	}

	if (limit_login_option('disable_pwd_reset_username') && !strpos($_POST['user_login'], '@')) {
		/* limit on pwd reset using user name */
		$limit_username = limit_login_option('pwd_reset_username_limit');

		/* use lowest limit */
		if (is_null($limit) || $limit > $limit_username)
			$limit = $limit_username;
	}

	if (is_null($limit)) {
		/* Current reset not limited */
		return $b;
	}

	/* Test if user have this level */
	if (!limit_login_user_has_level($userid, $limit))
		return $b;

	/* Not allowed -- use same error as retrieve_password() */
	$error = new WP_Error();
	$error->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.', 'limit-login-attempts'));
	return $error;
}


/*
 * Notification functions
 */

/* Email notification of lockout to admin (if configured) */
function limit_login_notify_email($username) {
	$ip = limit_login_get_address();
	$retries = limit_login_get_array('retries');

	/* Check if we are at the right nr to do notification
	 * 
	 * Todo: this always sends notification on long lockout (when $retries[$ip]
	 * is reset).
	 */
	if ( isset($retries[$ip])
	     && ( ($retries[$ip] / limit_login_option('allowed_retries'))
		  % limit_login_option('notify_email_after') ) != 0 ) {
		return;
	}

	/* Format message. First current lockout duration */
	if (!isset($retries[$ip])) {
		/* longer lockout */
		$count = limit_login_option('allowed_retries')
			* limit_login_option('allowed_lockouts');
		$lockouts = limit_login_option('allowed_lockouts');
		$time = round(limit_login_option('long_duration') / 3600);
		$when = sprintf(_n('%d hour', '%d hours', $time, 'limit-login-attempts'), $time);
	} else {
		/* normal lockout */
		$count = $retries[$ip];
		$lockouts = floor($count / limit_login_option('allowed_retries'));
		$time = round(limit_login_option('lockout_duration') / 60);
		$when = sprintf(_n('%d minute', '%d minutes', $time, 'limit-login-attempts'), $time);
	}

	$blogname = is_limit_login_multisite() ? get_site_option('site_name') : get_option('blogname');	

	$subject = sprintf(__("[%s] Too many failed login attempts", 'limit-login-attempts')
			   , $blogname);
	$message = sprintf(__("%d failed login attempts (%d lockout(s)) from IP: %s"
			      , 'limit-login-attempts') . "\r\n\r\n"
			   , $count, $lockouts, $ip);
	if ($username != '') {
		$message .= sprintf(__("Last user attempted: %s", 'limit-login-attempts')
				    . "\r\n\r\n" , $username);
	}
	$message .= sprintf(__("IP was blocked for %s", 'limit-login-attempts'), $when);

	$admin_email = is_limit_login_multisite() ? get_site_option('admin_email') : get_option('admin_email');

	@wp_mail($admin_email, $subject, $message);
}


/* Logging of lockout (if configured) */
function limit_login_notify_log($username) {
	$log = limit_login_get_array('logged');
	$ip = limit_login_get_address();

	/*
	 * Log format:
	 * [ip][0] time of last attempt
	 * [ip][1][user_name] number of lockouts for username
	 */
	if (isset($log[$ip])) {
		$log[$ip][0] = time();
		if (isset($log[$ip][1][$username]))
			$log[$ip][1][$username]++;
		else
			$log[$ip][1][$username] = 1;
	} else
		$log[$ip] = array(time(), array($username => 1));

	limit_login_store_array('logged', $log);
}


/* Handle notification in event of lockout */
function limit_login_notify($username) {
	$args = explode(',', limit_login_option('lockout_notify'));

	if (empty($args))
		return;

	foreach ($args as $mode) {
		switch (trim($mode)) {
		case 'email':
			limit_login_notify_email($username);
			break;
		case 'log':
			limit_login_notify_log($username);
			break;
		}
	}
}


/*
 * Handle (och filter) messages and errors shown
 */

/* Construct informative error message */
function limit_login_error_msg($lockout_option = 'lockouts', $msg = '') {
	$ip = limit_login_get_address();
	$lockouts = limit_login_get_array($lockout_option);

	if ($msg == '')
		$msg = __('<strong>ERROR</strong>: Too many failed login attempts.', 'limit-login-attempts') . ' ';

	if (!isset($lockouts[$ip]) || time() >= $lockouts[$ip]) {
		/* Huh? No lockout? */
		$msg .= __('Please try again later.', 'limit-login-attempts');
		return $msg;
	}

	$when = ceil(($lockouts[$ip] - time()) / 60);
	if ($when > 60) {
		$when = ceil($when / 60);
		$msg .= sprintf(_n('Please try again in %d hour.', 'Please try again in %d hours.', $when, 'limit-login-attempts'), $when);
	} else {
		$msg .= sprintf(_n('Please try again in %d minute.', 'Please try again in %d minutes.', $when, 'limit-login-attempts'), $when);
	}

	return $msg;
}


/* Construct retries remaining message */
function limit_login_retries_remaining_msg() {
	$ip = limit_login_get_address();
	$retries = limit_login_get_array('retries');
	$valid = limit_login_get_array('retries_valid');

	/* Should we show retries remaining? */
	if (!isset($retries[$ip]) || !isset($valid[$ip]) || time() > $valid[$ip]) {
		/* no: no valid retries */
		return '';
	}
	if (($retries[$ip] % limit_login_option('allowed_retries')) == 0 ) {
		/* no: already been locked out for these retries */
		return '';
	}

	$remaining = max((limit_login_option('allowed_retries') - ($retries[$ip] % limit_login_option('allowed_retries'))), 0);
	return sprintf(_n("<strong>%d</strong> attempt remaining.", "<strong>%d</strong> attempts remaining.", $remaining, 'limit-login-attempts'), $remaining);
}


/* Return current (error) message to show, if any */
function limit_login_get_message() {
	if (!is_limit_login_ok())
		return limit_login_error_msg();

	return limit_login_retries_remaining_msg();
}


/* Should we show errors and messages on this page? */
function should_limit_login_show_msg() {
	if (isset($_GET['key'])) {
		/* reset password */
		return false;
	}

	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

	return ( $action != 'lostpassword' && $action != 'retrievepassword'
		 && $action != 'resetpass' && $action != 'rp'
		 && $action != 'register' );
}


/* Fix up the error message before showing it */
function limit_login_fixup_error_messages($content) {
	global $limit_login_just_lockedout, $limit_login_nonempty_credentials, $limit_login_my_error_shown;

	if (!should_limit_login_show_msg())
		return $content;

	/*
	 * During lockout we do not want to show any other error messages (like
	 * unknown user or empty password) -- unless this was the attempt that
	 * locked us out.
	 */
	if (!is_limit_login_ok() && !$limit_login_just_lockedout) {
		return limit_login_error_msg();
	}

	/*
	 * We want to filter the messages 'Invalid username' and 'Invalid password'
	 * as that is an information leak regarding user account names.
	 *
	 * Also, if there are more than one error message, put an extra <br /> tag
	 * between them.
	 */
	$msgs = explode("<br />\n", $content);

	if (strlen(end($msgs)) == 0) {
		/* remove last entry empty string */
		array_pop($msgs);
	}

	$count = count($msgs);
	$my_warn_count = $limit_login_my_error_shown ? 1 : 0;

	if ($limit_login_nonempty_credentials && $count > $my_warn_count) {
		/* Replace error message, including ours if necessary */
		$content = __('<strong>ERROR</strong>: Incorrect username or password.', 'limit-login-attempts') . "<br />\n";
		if ($limit_login_my_error_shown) {
			$content .= "<br />\n" . limit_login_get_message() . "<br />\n";
		}
		return $content;
	} elseif ($count <= 1)
		return $content;

	$new = '';
	while ($count-- > 0) {
		$new .= array_shift($msgs) . "<br />\n";
		if ($count > 0) {
			$new .= "<br />\n";
		}
	}

	return $new;
}


/* Add a message to login page when necessary */
function limit_login_add_error_message() {
	global $error, $limit_login_my_error_shown;

	if (!should_limit_login_show_msg() || $limit_login_my_error_shown)
		return;

	$msg = limit_login_get_message();

	if ($msg != '') {
		$limit_login_my_error_shown = true;
		$error .= $msg;
	}

	return;
}


/* Keep track of if user or password are empty, to filter errors correctly */
function limit_login_track_credentials($user, $password) {
	global $limit_login_nonempty_credentials;

	$limit_login_nonempty_credentials = (!empty($user) && !empty($password));
}


/*
 * Utility functions
 */

/*
 * Require additional plugin file
 *
 * Do not EVER use this function with anything except constant string values.
 */
function limit_login_require_file($name) {
	require_once(plugin_dir_path(__FILE__) . 'limit-login-attempts-' . $name . '.php');
}


/* Extend name to be used in options table, etc */
function limit_login_name($name_part) {
	return 'limit_login_' . $name_part;
}


/* Get correct client IP address */
function limit_login_get_address($type_name = '') {
	$type = $type_name;
	if (empty($type))
		$type = limit_login_option('client_type');

	if (isset($_SERVER[$type]))
		return $_SERVER[$type];

	/*
	 * Not found. Did we get proxy type from option?
	 * If so, try to fall back to direct address.
	 */
	if ( empty($type_name) && $type == LIMIT_LOGIN_PROXY_ADDR
	     && isset($_SERVER[LIMIT_LOGIN_DIRECT_ADDR]) ) {

		/*
		 * NOTE: Even though we fall back to direct address -- meaning you
		 * can get a mostly working plugin when set to PROXY mode while in
		 * fact directly connected to Internet it is not safe!
		 *
		 * Client can itself send HTTP_X_FORWARDED_FOR header fooling us
		 * regarding which IP should be banned.
		 */

		return $_SERVER[LIMIT_LOGIN_DIRECT_ADDR];
	}
	
	return '';
}


/* Is this WP Multisite? */
function is_limit_login_multisite() {
	return function_exists('get_site_option') && function_exists('is_multisite') && is_multisite();
}


/*
 * Helpfunction to check ip in time array (lockout/valid)
 *
 * Returns true if array exists, ip is key in array, and value (time) is larger
 * than or equal to current time
 */
function limit_login_check_ip($check_array, $ip = null) {
	if (!$ip)
		$ip = limit_login_get_address();

	return (is_array($check_array) && isset($check_array[$ip])
		&& time() <= $check_array[$ip]);
}


/* Should this array autoload from the options table? */
function limit_login_is_array_autoload($array_name) {
	$autoload = array('lockouts');

	return in_array($array_name, $autoload);
}

/*
 * Get plugin stored array (create blank if it does not exist)
 * 
 * Used to store lockout and retry information, etc.
 */
function limit_login_get_array($array_name) {
	$real_array_name = limit_login_name($array_name);

	$a = get_option($real_array_name);

	if (is_array($a))
		return $a;

	return array();
}


/* Store plugin array */
function limit_login_store_array($array_name, $a) {
	$real_array_name = limit_login_name($array_name);

	/*
	 * Does option exist already?
	 * 
	 * This is so we can set correct autoload if necessary.
	 */
	$old_value = get_option($real_array_name);
	if ($old_value === false) {
		$autoload = limit_login_is_array_autoload($array_name) ? 'yes' : 'no';
		add_option($real_array_name, $a, '', $autoload);
	} else
		update_option($real_array_name, $a);
}


/* Setup (and return) plugin statistics array */
function limit_login_setup_statistics() {
	global $limit_login_statistics;

	if (isset($limit_login_statistics) && is_array($limit_login_statistics))
		return $limit_login_statistics;

	$limit_login_statistics = limit_login_get_array('statistics');
	return $limit_login_statistics;
}


/* Get specified statistics value */
function limit_login_statistic_get($name, $infotype = 'value') {
	$statistics = limit_login_setup_statistics();

	return isset($statistics[$name]) && isset($statistics[$name][$infotype]) ? $statistics[$name][$infotype] : 0;
}


/* Get specified statistics information */
function limit_login_statistic_get_info($name) {
	$statistics = limit_login_setup_statistics();

	if (isset($statistics[$name]))
		return $statistics[$name];

	return array('value' => 0, 'set' => 0, 'reset' => 0);
}


/* Set specified statistics value */
function limit_login_statistic_set($name, $value, $reset=false) {
	limit_login_setup_statistics();

	global $limit_login_statistics;

	if (isset($limit_login_statistics[$name]) && is_array($limit_login_statistics[$name])) {
		$limit_login_statistics[$name]['value'] = $value;
		if ($reset) {
			$limit_login_statistics[$name]['reset'] = time();
			$limit_login_statistics[$name]['set'] = 0;
		} else
			$limit_login_statistics[$name]['set'] = time();
	} else
		$limit_login_statistics[$name] = array('value' => $value, 'set' => time(), 'reset' => time());
	limit_login_store_array('statistics', $limit_login_statistics);
}


/* Increase specified statistics value */
function limit_login_statistic_inc($name) {
	limit_login_statistic_set($name, 1 + limit_login_statistic_get($name));
}
?>