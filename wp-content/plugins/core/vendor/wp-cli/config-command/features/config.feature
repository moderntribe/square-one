Feature: Manage wp-config.php file

  Scenario: List constants, globals and files included from wp-config.php
    Given an empty directory
    And WP files
    And a wp-config-extra.php file:
      """
      require_once 'custom-include.php';
      """
    And a custom-include.php file:
      """
      <?php // This won't work without this file being empty. ?>
      """
    When I run `wp core config {CORE_CONFIG_SETTINGS} --extra-php < wp-config-extra.php`
    Then STDOUT should contain:
      """
      Generated 'wp-config.php' file.
      """

    When I run `wp config get --fields=key,type`
    Then STDOUT should be a table containing rows:
      | key                | type     |
      | DB_NAME            | constant |
      | DB_USER            | constant |
      | DB_PASSWORD        | constant |
      | DB_HOST            | constant |
      | custom-include.php | includes |

    When I try `wp config get`
    Then STDOUT should be a table containing rows:
      | key | value | type |

  Scenario: Getting config should produce error when no config is found
    Given an empty directory

    When I try `wp config get`
    Then STDERR should be:
      """
      Error: 'wp-config.php' not found.
      """

    When I try `wp config path`
    Then STDERR should be:
      """
      Error: 'wp-config.php' not found.
      """

  Scenario: Get a wp-config.php file path
    Given a WP install
    When I try `wp config path`
    And STDOUT should contain:
      """
      wp-config.php
      """
    And STDERR should be empty
