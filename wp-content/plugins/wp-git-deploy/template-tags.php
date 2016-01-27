<?php

function get_git_revision() {
	$git = Git_Info_Plugin::get_instance();
	return $git->get_revision();
}

function get_git_branch() {
	$git = Git_Info_Plugin::get_instance();
	return $git->get_branch();
}

function get_svn_revision() {
	return get_git_revision();
}

function get_svn_branch() {
	return get_git_branch();
}

if ( !function_exists('get_version') ) {
	function get_version() {
		return apply_filters('get_version', get_git_revision());
	}
}