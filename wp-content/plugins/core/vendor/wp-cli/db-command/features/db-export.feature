Feature: Export a WordPress database

  Scenario: Database exports with random hash applied
    Given a WP install

    When I run `wp db export --porcelain`
    Then STDOUT should contain:
      """
      wp_cli_test-
      """
    And the wp_cli_test.sql file should not exist

  Scenario: Database export to a specified file path
    Given a WP install

    When I run `wp db export wp_cli_test.sql --porcelain`
    Then STDOUT should contain:
      """
      wp_cli_test.sql
      """
    And the wp_cli_test.sql file should exist

  Scenario: Exclude tables when exporting the dtabase
    Given a WP install

    When I run `wp db export wp_cli_test.sql --exclude_tables=wp_users --porcelain`
    Then the wp_cli_test.sql file should exist
    And the wp_cli_test.sql file should not contain:
      """
      wp_users
      """
    And the wp_cli_test.sql file should contain:
      """
      wp_options
      """
