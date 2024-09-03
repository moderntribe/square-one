# Plugins

The WordPress ecosystem includes thousands of plugins developed by third parties. After a reasonable review
of a plugin's features, performance, and security, we will happily use it on our projects to avoid reinventing
a perfectly good wheel.

## Composer and WordPress Packagist

As third-party plugins are maintained by third parties, we don't need to include them in our own source control system.
Just like any other third-party library, we install plugins using Composer (note that this applies to WordPress
core itself, too).

[WordPress Packagist](https://wpackagist.org/) maintains a mirror of the entire [WordPress Plugin
Directory](https://wordpress.org/plugins/), installable using Composer. Any public plugin can be required using
the name `wpackagist-plugin/<plugin-slug>` (e.g., `wpackagist-plugin/classic-editor`).

We cannot count on plugins in the WordPress Plugin Directory to use semantic versioning, so _always_ set a specific
version of the plugin in `composer.json`.

Update plugins by changing the version in `composer.json` and running `composer udpate`.

## Proprietary Plugins.

Advanced Cusotm Fields Pro is installed via Composer using [the plugin author's preferred method](https://www.advancedcustomfields.com/resources/installing-acf-pro-with-composer/), an auth.json file.
There is a `auth-sample.json` file in the project root. Copy this to `auth.json` and provide a valid license key to
allow composer to authenticate and install the plugin.

```
{
  "http-basic": {
    "connect.advancedcustomfields.com": {
      "username": "[your ACF Pro license key]",
      "password": "https://square1.tribe"
    }
  }
}
```

### Uncooperative Plugins

Unfortunately, there are some premium plugins that cannot be installed with Composer including Gravity Forms.
For these, we have to take the old fashioned approach of downloading the plugin, copying it into the project,
and committing the files.

All plugins are gitignored by default, so any plugins installed this way should be force added to the repo.

```
git add -Af wp-content/plugins/<plugin-dir>
```

## Force Activation

In some cases, a plugin should always be active. For example, the theme or another custom plugin may require it, or
the site would be broken without it.

While one can, technically speaking, force these plugins to be active by moving them to `mu-plugins`, we avoid this
for a few reasons.

1. We can no longer install the plugins using Composer
2. We have to build a separate loader to initialize the plugin
3. Some plugins simply break if they run as MU plugins, due to the different directory and loading sequence.

Instead, these required plugins should be added to the list in `\Force_Plugin_Activation::$force_active`. Any plugin
in this list will always be active. No one, not even a super admin, can deactivate it.

## Development Tools

WordPress plugins meant to aid local development can be installed locally and not committed to the git repository.
Install the plugins the traditional way with WP-CLI. Example:

```
so wp plugin install --activate debug-bar
```

Since all plugins are gitignored by default, this will not be added to the repo automatically when you run
`git add -A`. Note that many common development tools are also forced into an inactive state unless `WP_DEBUG`
is set to `true` in your `local-config.php`.
