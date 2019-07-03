# Tests for Square 1

Square one tests are fully dockerized, including a new container called `tribe-chrome` which provides a selenium-chrome
standalone image to power chromedriver for acceptance tests that need to use JavaScript.

## Setup

1. Create a database called `tribe_square1_tests` (you don't need to populate it with anything)
1. In the root of this whole repo, copy `tests-config-sample.php` to `tests-config.php`
1. Ensure that `dev/tests/.env` holds the correct values for connecting to your `tribe_square1_tests` database
1. Run `npm run codecept -- [args]` to execute any codeception command.
1. Run `npm run codecept:test -- acceptance` for acceptance tests
1. Run `npm run codecept:test -- functional` for functional tests
1. Run `npm run codecept:test -- integration` for integration tests
1. Run `npm run codecept:test -- unit` for unit tests
1. Run `npm run codecept:test -- webdriver` for webdriver tests

### Pro-tips

Set up an alias in your `.bashrc` or `.zshrc` file as follows: `alias tribetestintegration="dev/docker/exec.sh /application/www/dev/docker/codecept.sh run integration"`. Then you can do `tribetestintegration run`. This can be anything that makes your life easy, and you can create other commands for other test suites (such as `unit`)

### Testing tips

1. If you need to use Webdriver, you should create your own suite with `dev/docker/exec.sh /application/www/dev/docker/codecept.sh g:suite <your_feature_name>` in order to keep other tests running fast.

# Table of Contents

* [Overview](/docs/tests/README.md)
* [Codeception](/dev/tests/README.md)
* [Jest](/docs/tests/jest.md)
