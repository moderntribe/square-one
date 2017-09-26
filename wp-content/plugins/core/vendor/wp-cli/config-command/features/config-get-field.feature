Feature: Get the value of a constant or global defined in wp-config.php file

  Background:
    Given a WP install

  Scenario: Get the value of an existing wp-config.php constant
    When I try `wp config get --constant=DB_NAME`
    Then STDOUT should be:
      """
      wp_cli_test
      """
    And STDERR should be empty

  Scenario: Get the value of an existing wp-config.php global
    When I try `wp config get --global=table_prefix`
    Then STDOUT should be:
      """
      wp_
      """
    And STDERR should be empty

  Scenario: Get the value of a non existing wp-config.php constant
    When I try `wp config get --constant=FOO`
    Then STDERR should be:
      """
      Error: The 'FOO' constant is not defined in the wp-config.php file.
      """
    And STDOUT should be empty

  Scenario: Get the value of a non existing wp-config.php global
    When I try `wp config get --global=foo`
    Then STDERR should be:
      """
      Error: The 'foo' global is not defined in the wp-config.php file.
      """
    And STDOUT should be empty

  Scenario: Get the value of an existing wp-config.php constant with wrong case should yield an error
    When I try `wp config get --constant=db_name`
    Then STDERR should be:
      """
      Error: The 'db_name' constant is not defined in the wp-config.php file.
      """
    And STDOUT should be empty

  Scenario: Get the value of an existing wp-config.php global with wrong case should yield an error
    When I try `wp config get --global=TABLE_PREFIX`
    Then STDERR should be:
      """
      Error: The 'TABLE_PREFIX' global is not defined in the wp-config.php file.
      """
    And STDOUT should be empty

  Scenario: Get the value of an existing wp-config.php constant with some similarity should yield a helpful error
    When I try `wp config get --constant=DB_NOME`
    Then STDERR should be:
      """
      Error: The 'DB_NOME' constant is not defined in the wp-config.php file.
      Did you mean 'DB_NAME'?
      """
    And STDOUT should be empty

  Scenario: Get the value of an existing wp-config.php constant with some similarity should yield a helpful error
    When I try `wp config get --global=table_perfix`
    Then STDERR should be:
      """
      Error: The 'table_perfix' global is not defined in the wp-config.php file.
      Did you mean 'table_prefix'?
      """
    And STDOUT should be empty

  Scenario: Get the value of an existing wp-config.php constant with remote similarity should yield just an error
    When I try `wp config get --constant=DB_NOOOOZLE`
    Then STDERR should be:
      """
      Error: The 'DB_NOOOOZLE' constant is not defined in the wp-config.php file.
      """
    And STDOUT should be empty

  Scenario: Get the value of an existing wp-config.php global with remote similarity should yield just an error
    When I try `wp config get --global=tabre_peffix`
    Then STDERR should be:
      """
      Error: The 'tabre_peffix' global is not defined in the wp-config.php file.
      """
    And STDOUT should be empty

  Scenario: Trying to get the value of a constant and a global should yield an error
    When I try `wp config get --constant=DB_NAME --global=table_prefix`
    Then STDERR should be:
      """
      Error: Cannot request the value of a constant and a global at the same time.
      """
    And STDOUT should be empty
