#!/usr/bin/env sh

if git rev-parse --verify HEAD >/dev/null 2>&1
then
	against=HEAD
else
	# Initial commit: diff against an empty tree object
	against=$(git hash-object -t tree /dev/null)
fi

# If there are whitespace errors, print the offending file names and fail.
exec git diff-index --check --cached $against --
