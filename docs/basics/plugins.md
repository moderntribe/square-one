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

## Proprietary Plugins

Some commonly used plugins are not available publicly, but still provide an API to download a licensed copy. The
utility `ffraenz/private-composer-installer` allows us to provide our license key as an environment variable and
install a proprietary plugin using Composer.

We specifically use this to install [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/pro/). Note
that the plugin version listed in the `require` section of `composer.json` is meaningless, and should always be
set to `*`.

```
"advanced-custom-fields/advanced-custom-fields-pro": "*"
```

In the `repositories` section we see the real magic to install the plugin.

```
{
  "type": "package",
  "package": {
    "name": "advanced-custom-fields/advanced-custom-fields-pro",
    "version": "5.8.11",
    "type": "wordpress-plugin",
    "dist": {
      "type": "zip",
      "url": "https://connect.advancedcustomfields.com/index.php?a=download&p=pro&k={%WP_PLUGIN_ACF_KEY}&t={%VERSION}"
    },
    "require": {
      "ffraenz/private-composer-installer": "^3.0"
    }
  }
}
```

The `version` property of the package sets the version that will be installed. The license key comes from an
environment variable named `WP_PLUGIN_ACF_KEY`. By setting this in a `.env` file in the project's root directory,
Composer will pass the key to ACF's download API.

```
WP_PLUGIN_ACF_KEY='abcdefghijklmnopqrstuvwxyz123456789abcdefghijklmnopqrstuvwxyz01234567890'
```

Gravity Forms is a special case, in that we cannot set a URL directly in `composer.json`. Instead, we have to provide
a URL to a proxy that will request a temporary URL from another API. Its URL will look like:

```
"url": "https://composer.utility.mtribe.site/gravityforms/?key={%WP_PLUGIN_GF_KEY}&token={%WP_PLUGIN_GF_TOKEN}&t={%VERSION}"
```

Otherwise, it is configured exactly like ACF. Once again, the license key must be set in `.env`, with the name
`WP_PLUGIN_GF_KEY`. Additionally, it requires a token for the proxy service, named `WP_PLUGIN_GF_TOKEN`.

All of these values can be found in 1Password for use with Modern Tribe's projects.

### Uncooperative Plugins

Unfortunately, there are some premium plugins that cannot be installed with Composer. For these, we have to take
the old fashioned approach of downloading the plugin, copying it into the project, and committing the files.

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
