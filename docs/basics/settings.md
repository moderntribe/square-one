# Settings Pages

## Creating Settings Screens

As a first step to displaying settings in the WordPress admin, create a new settings screen with ACF. This
will be a class extending `\Tribe\Libs\ACF\ACF_Settings` that configures the screen. Later we'll add fields
to that screen.

An example settings page must implement methods from the `\Tribe\Libs\Settings\Settings_Builder` interface to
adjust configure the page.

```php
namespace Tribe\Example\Settings;

class Example extends \Tribe\Libs\ACF\ACF_Settings {
	public function get_title() {
		return __( 'Example Settings Page', 'tribe' );
	}

	public function get_capability() {
		// 'manage_options' is a good choice for most cases, allowing administrators to edit the page
		// 'manage_network' will limit to super admins on a multisite installation
		// 'publish_pages' will usually target editors
		// other capabilities can be used as appropriate
		return 'manage_options';
	}

	public function get_parent_slug() {
		return 'options-general.php';
	}

	protected function set_slug() {
		// This is optional. If not set, a slug will be auto-generated
		// from the parent + title
		$this->slug = 'example-settings';
	}
}
```

With the page thus configured, add it to the `\Tribe\Libs\Settings\Settings_Definer::PAGES` in
`\Tribe\Project\Settings\Settings_Definer`.

```php
\Tribe\Libs\Settings\Settings_Definer::PAGES => DI\add( [
    DI\get( Example::class ),
] )
```

## Adding Settings Fields

Fields are added to settings screens in the same way that meta fields are added to post types or taxonomies. Read
about [Object Meta](./object-meta.md) for more details.

The object meta class for the settings group will be defined in `\Tribe\Project\Object_Meta\Object_Meta_Definer`
with a `settings_pages` argument pointing to the slug of the settings page(s) to which it will be registered.


```php
Example::class => static function ( ContainerInterface $container ) {
  return new Example( [
    'settings_pages' => [ $container->get( Settings\Example::class )->get_slug() ],
  ] );
}
```

## Network Settings Screens

As ACF does not support network settings screens, we use the "old-fashioned" method of the WordPress settings API.

First, create a class for your network settings screen. It should have a `register_screen()` callback hooked into
the `network_admin_menu` action. Within this callback, use `add_submenu_page()`, `add_settings_section()`, and
`add_settings_field()` as necessary to configure the page appropriately. There is no need to use `register_setting()`,
as it is unused for network admin pages.

The page's render callback will always follow the same pattern:

```php
public function render_screen() {
    $title  = __( 'My Page Title', 'tribe' );
    $action = network_admin_url( 'edit.php?action=' . self::NAME );
    ob_start();

    $this->settings_errors();

    printf( "<form action='%s' method='post'>", esc_url( $action ) );

    // network admin won't do this automatically
    printf( '<input type="hidden" name="action" value="%s" />', esc_attr( self::NAME ) );
    wp_nonce_field( self::NAME );

    do_settings_sections( self::NAME );
    submit_button( __( 'Submit', 'tribe' ) );
    echo "</form>";

    $content = ob_get_clean();

    printf( '<div class="wrap"><h1>%s</h1>%s</div>', $title, $content );
}

private function settings_errors() {
    global $wp_settings_errors;
    $transient = get_transient( 'settings_errors' );
    if ( $transient ) {
        $wp_settings_errors = array_merge( (array) $wp_settings_errors, get_transient( 'settings_errors' ) );
        delete_transient( 'settings_errors' );
    }
    settings_errors();
}
```

To handle the form submission, hook into `'network_admin_edit_' . self::NAME` with a handler method.

```php
public function handle_submission() {
    $errors = $this->validate_submission( $_POST );

    if ( count( $errors->get_error_codes() ) > 0 ) {
        foreach ( $errors->get_error_codes() as $code ) {
            foreach ( $errors->get_error_messages( $code ) as $message ) {
                add_settings_error( self::NAME, $code, $message, 'error' );
            }
        }
    } else {

        // ... do things to save the form submission

        add_settings_error( self::NAME, 'settings-saved', __( 'Your settings have been saved.', 'tribe' ), 'updated' );
    }
    set_transient( 'settings_errors', get_settings_errors(), 30 );

    wp_safe_redirect( network_admin_url( 'sites.php?page=' . self::NAME ), 303 );
    exit();
}

/**
 * @param array $submission
 *
 * @return \WP_Error
 */
private function validate_submission( $submission ) {
    $error = new \WP_Error();
    if ( ! isset( $submission[ '_wpnonce' ] ) || ! wp_verify_nonce( $submission[ '_wpnonce' ], self::NAME ) ) {
        $error->add( 'invalid_nonce', __( 'Error while saving. Please try again.', 'tribe' ) );
    }

    return $error;
}
```
