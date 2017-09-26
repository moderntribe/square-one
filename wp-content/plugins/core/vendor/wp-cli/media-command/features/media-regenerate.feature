Feature: Regenerate WordPress attachments

  Background:
    Given a WP install

  Scenario: Regenerate all images while none exists
    When I try `wp media regenerate --yes`
    Then STDERR should contain:
      """
      No images found.
      """

  Scenario: Delete existing thumbnails when media is regenerated
    Given download:
      | path                        | url                                              |
      | {CACHE_DIR}/large-image.jpg | http://wp-cli.org/behat-data/large-image.jpg     |
    And a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 125, 125, true );
      });
      """
    And I run `wp option update uploads_use_yearmonth_folders 0`

    When I run `wp media import {CACHE_DIR}/large-image.jpg --title="My imported attachment" --porcelain`
    Then save STDOUT as {ATTACHMENT_ID}
    And the wp-content/uploads/large-image-125x125.jpg file should exist

    Given a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 200, 200, true );
      });
      """
    When I run `wp media regenerate --yes`
    Then STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """
    And the wp-content/uploads/large-image-125x125.jpg file should not exist
    And the wp-content/uploads/large-image-200x200.jpg file should exist

  Scenario: Skip deletion of existing thumbnails when media is regenerated
    Given download:
      | path                        | url                                              |
      | {CACHE_DIR}/large-image.jpg | http://wp-cli.org/behat-data/large-image.jpg     |
    And a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 125, 125, true );
      });
      """
    And I run `wp option update uploads_use_yearmonth_folders 0`

    When I run `wp media import {CACHE_DIR}/large-image.jpg --title="My imported attachment" --porcelain`
    Then save STDOUT as {ATTACHMENT_ID}
    And the wp-content/uploads/large-image-125x125.jpg file should exist

    Given a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 200, 200, true );
      });
      """
    When I run `wp media regenerate --skip-delete --yes`
    Then STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """
    And the wp-content/uploads/large-image-125x125.jpg file should exist
    And the wp-content/uploads/large-image-200x200.jpg file should exist

  @require-wp-4.7.3 @require-extension-imagick
  Scenario: Delete existing thumbnails when media including PDF is regenerated
    Given download:
      | path                              | url                                                   |
      | {CACHE_DIR}/large-image.jpg       | http://wp-cli.org/behat-data/large-image.jpg          |
      | {CACHE_DIR}/minimal-us-letter.pdf | http://wp-cli.org/behat-data/minimal-us-letter.pdf    |
    And a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 125, 125, true );
        add_filter( 'fallback_intermediate_image_sizes', function( $fallback_sizes ){
          $fallback_sizes[] = 'test1';
          return $fallback_sizes;
        });
      });
      """
    And I run `wp option update uploads_use_yearmonth_folders 0`

    When I run `wp media import {CACHE_DIR}/large-image.jpg --title="My imported attachment" --porcelain`
    Then save STDOUT as {ATTACHMENT_ID}
    And the wp-content/uploads/large-image-125x125.jpg file should exist

    When I run `wp media import {CACHE_DIR}/minimal-us-letter.pdf --title="My imported PDF attachment" --porcelain`
    Then save STDOUT as {PDF_ATTACHMENT_ID}
    And the wp-content/uploads/minimal-us-letter-pdf-125x125.jpg file should exist

    Given a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 200, 200, true );
        add_filter( 'fallback_intermediate_image_sizes', function( $fallback_sizes ){
          $fallback_sizes[] = 'test1';
          return $fallback_sizes;
        });
      });
      """
    When I run `wp media regenerate --yes`
    Then STDOUT should contain:
      """
      Success: Regenerated 2 of 2 images.
      """
    And the wp-content/uploads/large-image-125x125.jpg file should not exist
    And the wp-content/uploads/large-image-200x200.jpg file should exist
    And the wp-content/uploads/minimal-us-letter-pdf-125x125.jpg file should not exist
    And the wp-content/uploads/minimal-us-letter-pdf-200x200.jpg file should exist

  @require-wp-4.7.3 @require-extension-imagick
  Scenario: Skip deletion of existing thumbnails when media including PDF is regenerated
    Given download:
      | path                              | url                                                   |
      | {CACHE_DIR}/large-image.jpg       | http://wp-cli.org/behat-data/large-image.jpg          |
      | {CACHE_DIR}/minimal-us-letter.pdf | http://wp-cli.org/behat-data/minimal-us-letter.pdf    |
    And a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 125, 125, true );
        add_filter( 'fallback_intermediate_image_sizes', function( $fallback_sizes ){
          $fallback_sizes[] = 'test1';
          return $fallback_sizes;
        });
      });
      """
    And I run `wp option update uploads_use_yearmonth_folders 0`

    When I run `wp media import {CACHE_DIR}/large-image.jpg --title="My imported attachment" --porcelain`
    Then save STDOUT as {ATTACHMENT_ID}
    And the wp-content/uploads/large-image-125x125.jpg file should exist

    When I run `wp media import {CACHE_DIR}/minimal-us-letter.pdf --title="My imported PDF attachment" --porcelain`
    Then save STDOUT as {PDF_ATTACHMENT_ID}
    And the wp-content/uploads/minimal-us-letter-pdf-125x125.jpg file should exist

    Given a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 200, 200, true );
        add_filter( 'fallback_intermediate_image_sizes', function( $fallback_sizes ){
          $fallback_sizes[] = 'test1';
          return $fallback_sizes;
        });
      });
      """
    When I run `wp media regenerate --skip-delete --yes`
    Then STDOUT should contain:
      """
      Success: Regenerated 2 of 2 images.
      """
    And the wp-content/uploads/large-image-125x125.jpg file should exist
    And the wp-content/uploads/large-image-200x200.jpg file should exist
    And the wp-content/uploads/minimal-us-letter-pdf-125x125.jpg file should exist
    And the wp-content/uploads/minimal-us-letter-pdf-1-200x200.jpg file should exist

  Scenario: Provide helpful error messages when media can't be regenerated
    Given download:
      | path                        | url                                              |
      | {CACHE_DIR}/large-image.jpg | http://wp-cli.org/behat-data/large-image.jpg     |
    And a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 125, 125, true );
      });
      """
    And I run `wp option update uploads_use_yearmonth_folders 0`

    When I run `wp media import {CACHE_DIR}/large-image.jpg --title="My imported attachment" --porcelain`
    Then save STDOUT as {ATTACHMENT_ID}
    And the wp-content/uploads/large-image-125x125.jpg file should exist

    When I run `rm wp-content/uploads/large-image.jpg`
    Then STDOUT should be empty

    When I try `wp media regenerate --yes`
    Then STDERR should be:
      """
      Warning: Can't find "My imported attachment" (ID {ATTACHMENT_ID}).
      Error: No images regenerated.
      """

  Scenario: Only regenerate images which are missing sizes
    Given download:
      | path                        | url                                              |
      | {CACHE_DIR}/large-image.jpg | http://wp-cli.org/behat-data/large-image.jpg     |
    And a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 125, 125, true );
      });
      // Handle WP < 4.4 when there was no dash before numbers (changeset 35276).
      add_filter( 'wp_handle_upload', function ( $info, $upload_type = null ) {
        if ( ( $new_file = str_replace( 'image1.jpg', 'image-1.jpg', $info['file'] ) ) !== $info['file'] ) {
            rename( $info['file'], $new_file );
            $info['file'] = $new_file;
            $info['url'] = str_replace( 'image1.jpg', 'image-1.jpg', $info['url'] );
        }
        return $info;
      } );
      """
    And I run `wp option update uploads_use_yearmonth_folders 0`

    When I run `wp media import {CACHE_DIR}/large-image.jpg --title="My imported attachment" --porcelain`
    Then save STDOUT as {ATTACHMENT_ID}
    And the wp-content/uploads/large-image-125x125.jpg file should exist

    When I run `wp media import {CACHE_DIR}/large-image.jpg --title="My second imported attachment" --porcelain`
    Then save STDOUT as {SECOND_ATTACHMENT_ID}

    When I run `rm wp-content/uploads/large-image-125x125.jpg`
    Then the wp-content/uploads/large-image-125x125.jpg file should not exist

    When I run `wp media regenerate --only-missing --yes`
    Then STDOUT should contain:
      """
      Found 2 images to regenerate.
      """
    And STDOUT should contain:
      """
      /2 No thumbnail regeneration needed for "My second imported attachment"
      """
    And STDOUT should contain:
      """
      /2 Regenerated thumbnails for "My imported attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 2 of 2 images
      """

    # If run again, nothing should happen.
    When I run `wp media regenerate --only-missing --yes`
    Then STDOUT should contain:
      """
      Found 2 images to regenerate.
      """
    And STDOUT should contain:
      """
      /2 No thumbnail regeneration needed for "My second imported attachment"
      """
    And STDOUT should contain:
      """
      /2 No thumbnail regeneration needed for "My imported attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 2 of 2 images
      """

    # Change dimensions of "test1".
    Given a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 200, 200, true );
      });
      """
    Then the wp-content/uploads/large-image-125x125.jpg file should exist
    And the wp-content/uploads/large-image-1-125x125.jpg file should exist
    And the wp-content/uploads/large-image-200x200.jpg file should not exist
    And the wp-content/uploads/large-image-1-200x200.jpg file should not exist

    # Now thumbnails for both should be regenerated (and the old ones left as --only-missing sets --skip-delete).
    When I run `wp media regenerate --only-missing --yes`
    Then STDOUT should contain:
      """
      Found 2 images to regenerate.
      """
    And STDOUT should contain:
      """
      /2 Regenerated thumbnails for "My second imported attachment"
      """
    And STDOUT should contain:
      """
      /2 Regenerated thumbnails for "My imported attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 2 of 2 images
      """
    Then the wp-content/uploads/large-image-125x125.jpg file should exist
    And the wp-content/uploads/large-image-1-125x125.jpg file should exist
    And the wp-content/uploads/large-image-200x200.jpg file should exist
    And the wp-content/uploads/large-image-1-200x200.jpg file should exist

    # Check metadata updated.
    When I run `wp post meta get {ATTACHMENT_ID} _wp_attachment_metadata --format=json | grep -o '"test1":{[^}]*"file":"large-image-200x200.jpg"'`
    Then STDOUT should contain:
      """
      "file":"large-image-200x200.jpg"
      """
    When I run `wp post meta get {SECOND_ATTACHMENT_ID} _wp_attachment_metadata --format=json | grep -o '"test1":{[^}]*"file":"large-image-1-200x200.jpg"'`
    Then STDOUT should contain:
      """
      "file":"large-image-1-200x200.jpg"
      """

  Scenario: Regenerate images which are missing globally-defined image sizes
    Given download:
      | path                        | url                                              |
      | {CACHE_DIR}/large-image.jpg | http://wp-cli.org/behat-data/large-image.jpg     |
    And I run `wp option update uploads_use_yearmonth_folders 0`

    When I run `wp media import {CACHE_DIR}/large-image.jpg --title="My imported attachment" --porcelain`
    Then save STDOUT as {ATTACHMENT_ID}
    And the wp-content/uploads/large-image-125x125.jpg file should not exist

    Given a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 125, 125, true );
      });
      """

    When I run `wp media regenerate --only-missing --yes`
    Then STDOUT should contain:
      """
      Found 1 image to regenerate.
      """
    And STDOUT should contain:
      """
      1/1 Regenerated thumbnails for "My imported attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """
    And the wp-content/uploads/large-image-125x125.jpg file should exist

    When I run `wp media regenerate --only-missing --yes`
    Then STDOUT should contain:
      """
      Found 1 image to regenerate
      """
    And STDOUT should contain:
      """
      1/1 No thumbnail regeneration needed for "My imported attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """
    And the wp-content/uploads/large-image-125x125.jpg file should exist

  Scenario: Only regenerate images that are missing if the size should exist
    Given download:
      | path                   | url                                         |
      | {CACHE_DIR}/canola.jpg | http://wp-cli.org/behat-data/canola.jpg     |
    And a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 500, 500, true ); // canola.jpg is 640x480.
        add_image_size( 'test2', 400, 400, true );
      });
      """
    And I run `wp option update uploads_use_yearmonth_folders 0`

    When I run `wp media import {CACHE_DIR}/canola.jpg --title="My imported attachment" --porcelain`
    Then the wp-content/uploads/canola-500x500.jpg file should not exist
    And the wp-content/uploads/canola-400x400.jpg file should exist

    When I run `wp media regenerate --only-missing --yes`
    Then STDOUT should contain:
      """
      Found 1 image to regenerate.
      """
    And STDOUT should contain:
      """
      1/1 No thumbnail regeneration needed for "My imported attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """
    And the wp-content/uploads/canola-500x500.jpg file should not exist
    And the wp-content/uploads/canola-400x400.jpg file should exist

  @require-wp-4.7.3 @require-extension-imagick
  Scenario: Only regenerate PDF previews that are missing if the size should exist
    Given download:
      | path                              | url                                                   |
      | {CACHE_DIR}/minimal-us-letter.pdf | http://wp-cli.org/behat-data/minimal-us-letter.pdf    |
    And a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 1100, 1100, true ); // minimal-us-letter.pdf is 1088x1408 at 128 dpi.
        add_image_size( 'test2', 1000, 1000, true );
        add_filter( 'fallback_intermediate_image_sizes', function( $fallback_sizes ){
          $fallback_sizes[] = 'test1';
          $fallback_sizes[] = 'test2';
          return $fallback_sizes;
        });
      });
      """
    And I run `wp option update uploads_use_yearmonth_folders 0`

    When I run `wp media import {CACHE_DIR}/minimal-us-letter.pdf --title="My imported PDF attachment" --porcelain`
    Then the wp-content/uploads/minimal-us-letter-pdf-1100x1100.jpg file should not exist
    And the wp-content/uploads/minimal-us-letter-pdf-1000x1000.jpg file should exist

    When I run `wp media regenerate --only-missing --yes`
    Then STDOUT should contain:
      """
      Found 1 image to regenerate.
      """
    And STDOUT should contain:
      """
      1/1 No thumbnail regeneration needed for "My imported PDF attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """
    And the wp-content/uploads/minimal-us-letter-pdf-1100x1100.jpg file should not exist
    And the wp-content/uploads/minimal-us-letter-pdf-1000x1000.jpg file should exist

  Scenario: Only regenerate images that are missing if it has thumbnails
    Given download:
      | path                             | url                                               |
      | {CACHE_DIR}/white-150-square.jpg | http://wp-cli.org/behat-data/white-150-square.jpg |
    And I run `wp option update uploads_use_yearmonth_folders 0`
    And a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      // Use GD as Imagick editor for WP < 4.2 would produce thumbnails (changeset 31576).
      add_filter( 'wp_image_editors', function ( $image_editors ) {
          return array( 'WP_Image_Editor_GD' );
      } );
      """

    When I run `wp media import {CACHE_DIR}/white-150-square.jpg --title="My imported attachment" --porcelain`
    Then the wp-content/uploads/white-150-square-150x150.jpg file should not exist

    When I run `wp media regenerate --only-missing --yes`
    Then STDOUT should contain:
      """
      Found 1 image to regenerate.
      """
    And STDOUT should contain:
      """
      1/1 No thumbnail regeneration needed for "My imported attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """
    And the wp-content/uploads/white-150-square-150x150.jpg file should not exist

  Scenario: Regenerate a specific image size
    Given download:
      | path                        | url                                          |
      | {CACHE_DIR}/canola.jpg      | http://wp-cli.org/behat-data/canola.jpg      |
    And a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'too_big', 4000, 4000, true );
      });
      """
    And I run `wp option update uploads_use_yearmonth_folders 0`

    # Import without "test1" image size.
    When I run `wp media import {CACHE_DIR}/canola.jpg --title="My imported attachment" --porcelain`
    And save STDOUT as {ATTACHMENT_ID}
    Then the wp-content/uploads/canola-300x225.jpg file should exist
    And the wp-content/uploads/canola-400x400.jpg file should not exist

    # Add "test1" image size.
    Given a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 400, 400, true );
        add_image_size( 'too_big', 4000, 4000, true );
      });
      """

    # Run for "medium" size only if missing - nothing should happen.
    When I run `wp media regenerate --image_size=medium --only-missing --yes`
    Then STDOUT should contain:
      """
      Found 1 image to regenerate
      """
    And STDOUT should contain:
      """
      1/1 No "medium" thumbnail regeneration needed for "My imported attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """
    And the wp-content/uploads/canola-300x225.jpg file should exist
    And the wp-content/uploads/canola-400x400.jpg file should not exist

    # Remove "medium" image size file.
    When I run `rm wp-content/uploads/canola-300x225.jpg`
    Then the wp-content/uploads/canola-300x225.jpg file should not exist

    # Run for "test1" size only if missing - should be generated.
    When I run `wp media regenerate --image_size=test1 --only-missing --yes`
    Then STDOUT should contain:
      """
      Found 1 image to regenerate
      """
    And STDOUT should contain:
      """
      1/1 Regenerated "test1" thumbnail for "My imported attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """
    And the wp-content/uploads/canola-300x225.jpg file should not exist
    And the wp-content/uploads/canola-400x400.jpg file should exist

    # Check metadata consistent.
    When I run `wp post meta get {ATTACHMENT_ID} _wp_attachment_metadata --format=json | grep -o '"test1":{[^}]*"file":"canola-400x400.jpg"'`
    Then STDOUT should contain:
      """
      "file":"canola-400x400.jpg"
      """

    # Regenerate "medium" image size removed above - should be regenerated.
    When I run `wp media regenerate --image_size=medium --only-missing --yes`
    Then STDOUT should contain:
      """
      Found 1 image to regenerate
      """
    And STDOUT should contain:
      """
      1/1 Regenerated "medium" thumbnail for "My imported attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """
    And the wp-content/uploads/canola-300x225.jpg file should exist

    # Check metadata consistent.
    When I run `wp post meta get {ATTACHMENT_ID} _wp_attachment_metadata --format=json | grep -o '"medium":{[^}]*"file":"canola-300x225.jpg"'`
    Then STDOUT should contain:
      """
      "file":"canola-300x225.jpg"
      """

    # Regenerate "medium" image size whether missing or not - should be regenerated.
    When I run `wp media regenerate --image_size=medium --yes`
    Then STDOUT should contain:
      """
      Found 1 image to regenerate
      """
    And STDOUT should contain:
      """
      1/1 Regenerated "medium" thumbnail for "My imported attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """
    And the wp-content/uploads/canola-300x225.jpg file should exist

    # Change "test1" image size.
    Given a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 350, 350, true );
        add_image_size( 'too_big', 4000, 4000, true );
      });
      """

    # Regenerate "test1" image size only if missing (which also sets --skip-delete) - should be regenerated and 400x400 should still exist.
    When I run `wp media regenerate --image_size=test1 --only-missing --yes`
    Then STDOUT should contain:
      """
      Found 1 image to regenerate
      """
    And STDOUT should contain:
      """
      1/1 Regenerated "test1" thumbnail for "My imported attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """
    And the wp-content/uploads/canola-300x225.jpg file should exist
    And the wp-content/uploads/canola-350x350.jpg file should exist
    And the wp-content/uploads/canola-400x400.jpg file should exist

    # Check metadata updated.
    When I run `wp post meta get {ATTACHMENT_ID} _wp_attachment_metadata --format=json | grep -o '"test1":{[^}]*"file":"canola-350x350.jpg"'`
    Then STDOUT should contain:
      """
      "file":"canola-350x350.jpg"
      """

    # Change "test1" image size again.
    Given a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 380, 380, true );
        add_image_size( 'too_big', 4000, 4000, true );
      });
      """

    # Regenerate "test1" image size only if missing and with explicit --skip-delete - should be regenerated and 350x350 and 400x400 should still exist.
    When I run `wp media regenerate --image_size=test1 --only-missing --skip-delete --yes`
    Then STDOUT should contain:
      """
      Found 1 image to regenerate
      """
    And STDOUT should contain:
      """
      1/1 Regenerated "test1" thumbnail for "My imported attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """
    And the wp-content/uploads/canola-300x225.jpg file should exist
    And the wp-content/uploads/canola-350x350.jpg file should exist
    And the wp-content/uploads/canola-380x380.jpg file should exist
    And the wp-content/uploads/canola-400x400.jpg file should exist

    # Check metadata updated.
    When I run `wp post meta get {ATTACHMENT_ID} _wp_attachment_metadata --format=json | grep -o '"test1":{[^}]*"file":"canola-380x380.jpg"'`
    Then STDOUT should contain:
      """
      "file":"canola-380x380.jpg"
      """

    # The "too_big" thumbnail is never created so nothing should happen.
    When I run `wp media regenerate --image_size=too_big --yes`
    Then STDOUT should contain:
      """
      Found 1 image to regenerate
      """
    And STDOUT should contain:
      """
      1/1 No "too_big" thumbnail regeneration needed for "My imported attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """

  @require-wp-4.7.3 @require-extension-imagick
  Scenario: Regenerate a specific image size for a PDF attachment
    Given download:
      | path                              | url                                                   |
      | {CACHE_DIR}/minimal-us-letter.pdf | http://wp-cli.org/behat-data/minimal-us-letter.pdf    |
    And a wp-content/mu-plugins/media-settings.php file:
      """
      <?php
      add_action( 'after_setup_theme', function(){
        add_image_size( 'test1', 400, 400, true );
        add_image_size( 'not_in_fallback', 300, 300, true );
        add_filter( 'fallback_intermediate_image_sizes', function( $fallback_sizes ){
          $fallback_sizes[] = 'test1';
          return $fallback_sizes;
        });
      });
      """
    And I run `wp option update uploads_use_yearmonth_folders 0`

    When I run `wp media import {CACHE_DIR}/minimal-us-letter.pdf --title="My imported PDF attachment" --porcelain`
    And save STDOUT as {ATTACHMENT_ID}
    Then the wp-content/uploads/minimal-us-letter-pdf-116x150.jpg file should exist
    And the wp-content/uploads/minimal-us-letter-pdf-400x400.jpg file should exist

    # Remove "thumbnail" image size and run for "test1" size - nothing should happen.
    When I run `rm wp-content/uploads/minimal-us-letter-pdf-116x150.jpg`
    And I run `wp media regenerate --image_size=test1 --only-missing --yes`
    Then STDOUT should contain:
      """
      Found 1 image to regenerate
      """
    And STDOUT should contain:
      """
      1/1 No "test1" thumbnail regeneration needed for "My imported PDF attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """
    And the wp-content/uploads/minimal-us-letter-pdf-1-116x150.jpg file should not exist

    # Remove "test1" image size and run for "test1" size only if missing - should be regenerated.
    When I run `rm wp-content/uploads/minimal-us-letter-pdf-400x400.jpg`
    And I run `wp media regenerate --image_size=test1 --only-missing --yes`
    Then STDOUT should contain:
      """
      Found 1 image to regenerate
      """
    And STDOUT should contain:
      """
      1/1 Regenerated "test1" thumbnail for "My imported PDF attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """
    And the wp-content/uploads/minimal-us-letter-pdf-1-116x150.jpg file should not exist
    And the wp-content/uploads/minimal-us-letter-pdf-1-400x400.jpg file should exist

    # Check metadata updated.
    When I run `wp post meta get {ATTACHMENT_ID} _wp_attachment_metadata --format=json | grep -o '"test1":{[^}]*"file":"minimal-us-letter-pdf-1-400x400.jpg"'`
    Then STDOUT should contain:
      """
      "file":"minimal-us-letter-pdf-1-400x400.jpg"
      """

    # Regenerate "test1" image size whether missing or not - should be regenerated.
    # But skip deleting the existing thumbnail so its version increments.
    When I run `wp media regenerate --image_size=test1 --skip-delete --yes`
    Then STDOUT should contain:
      """
      Found 1 image to regenerate
      """
    And STDOUT should contain:
      """
      1/1 Regenerated "test1" thumbnail for "My imported PDF attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """
    And the wp-content/uploads/minimal-us-letter-pdf-2-116x150.jpg file should not exist
    And the wp-content/uploads/minimal-us-letter-pdf-1-400x400.jpg file should exist
    And the wp-content/uploads/minimal-us-letter-pdf-2-400x400.jpg file should exist

    # Check metadata updated with incremented version.
    When I run `wp post meta get {ATTACHMENT_ID} _wp_attachment_metadata --format=json | grep -o '"test1":{[^}]*"file":"minimal-us-letter-pdf-2-400x400.jpg"'`
    Then STDOUT should contain:
      """
      "file":"minimal-us-letter-pdf-2-400x400.jpg"
      """

    # The "not_in_fallback" thumbnail is never created for PDFs so nothing should happen.
    When I run `wp media regenerate --image_size=not_in_fallback --yes`
    Then STDOUT should contain:
      """
      Found 1 image to regenerate
      """
    And STDOUT should contain:
      """
      1/1 No "not_in_fallback" thumbnail regeneration needed for "My imported PDF attachment"
      """
    And STDOUT should contain:
      """
      Success: Regenerated 1 of 1 images.
      """

  Scenario: Provide error message when non-existent image size requested for regeneration
    When I try `wp media regenerate --image_size=test1`
    Then STDERR should be:
      """
      Error: Unknown image size "test1".
      """
