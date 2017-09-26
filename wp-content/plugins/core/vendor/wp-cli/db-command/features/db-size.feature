# Assumes wp_cli_test has a database size of around 655,360 bytes.

Feature: Display database size

  Scenario: Display only database size for a WordPress install
    Given a WP install

    When I run `wp db size`
    Then STDOUT should contain:
      """
      wp_cli_test
      """

    And STDOUT should contain:
      """
      KB
      """

  Scenario: Display only table sizes for a WordPress install
    Given a WP install

    When I run `wp db size --tables`
    Then STDOUT should contain:
      """
      wp_posts	80 KB
      """

    But STDOUT should not contain:
      """
      wp_cli_test
      """

  Scenario: Display only database size in bytes for a WordPress install
    Given a WP install

    When I run `wp db size --size_format=b`
    Then STDOUT should be a number


  Scenario: Display only database size in kilobytes for a WordPress install
    Given a WP install

    When I run `wp db size --size_format=kb`
    Then STDOUT should be a number


  Scenario: Display only database size in megabytes for a WordPress install
    Given a WP install

    When I run `wp db size --size_format=mb`
    Then STDOUT should be a number
