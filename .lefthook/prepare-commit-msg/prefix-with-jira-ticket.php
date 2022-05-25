#!/usr/bin/env php
<?php declare(strict_types=1);

/**
 * Prefixes commit messages with [JIRA_TICKET]: $original_commit_message.
 *
 * @example [SQONE-123]: Fixing some bug
 *
 * @link https://git-scm.com/docs/githooks#_prepare_commit_msg
 */

if ( ! function_exists( 'str_starts_with' ) ) {
	function str_starts_with( string $haystack, string $needle ): bool {
		return 0 === strncmp( $haystack, $needle, strlen( $needle ) );
	}
}

function is_valid_commit_source( string $source ): bool {
	$invalid_sources = [
		'merge'  => true,
		'squash' => true,
	];

	return ! isset( $invalid_sources[ $source ] );
}

function parse_ticket( string $branch ): string {
	if ( preg_match( '/[A-Z]{2,}-\d+/i', $branch, $matches ) ) {
		return $matches[0];
	}

	return '';
}

function is_protected_branch( string $branch ): bool {
	$protected_branches = [
		'main',
		'master',
		'develop',
		'sprint/',
	];

	foreach ( $protected_branches as $protected_branch ) {
		if ( str_starts_with( $branch, $protected_branch ) ) {
			return true;
		}
	}

	return false;
}

/**
 * @var string[] $argv {
 *      Arguments coming in from the git prepare-commit-msg hook.
 *
 *      @type string $1 The relative file path to where git stores the commit message, e.g. .git/COMMIT_MSG.
 *      @type string $2 The source of the commit: message, template, merge, squash, commit.
 * }
 */
$commit_file    = $argv[1];
$commit_source  = $argv[2];
$commit_message = file_get_contents( $commit_file );
$branch         = exec( 'git symbolic-ref --short HEAD' );

if ( $commit_message && $branch && is_valid_commit_source( $commit_source ) && ! is_protected_branch( $branch ) ) {
	$ticket = parse_ticket( $branch );

	if ( ! empty( $ticket ) ) {
		$commit = sprintf( '[%s]: %s', $ticket, $commit_message );
		file_put_contents( $commit_file, $commit );
	}
}
