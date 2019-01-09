# Tests for Square 1

## Setup

1. Create a database called `aaa_tests` (you don't need to populate it with anything)
1. In the root of this whole repo, copy `tests-config-sample.php` to `tests-config.php`
1. `cd dev/tests`
1. Run `sh ./composer.sh install`
1. Ensure that `dev/tests/.env` holds the correct values for connecting to your `tribe_square1_tests` database
1. Run `vendor/codeception/codeception/codecept run`

### Pro-tips

Set up an alias in your `.bashrc` or `.zshrc` file as follows: `alias codecept="vendor/codeception/codeception/codecept"`