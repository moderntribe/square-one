WP-CLI Autoload Splitter Composer Plugin
=======================================

This is a custom autoloader generator for [WP-CLI](https://wp-cli.org) that generates two separate autoloaders that split up the autoloadable classes. This allows these independent groups of autoloadable classes to be registered at different times in the code execution path.

Using the default settings, it will produce the following two files:

1. `vendor/autoload_framework.php`
2. `vendor/autoload_commands.php`

Usage
-----

In your project's `composer.json`, require the `wp-cli/autoload-splitter`, and then optionally add the `"extra"` as needed to customize default behavior:

```json
{
    "require": {
        "wp-cli/autoload-splitter": "^0.1"
    },
    "extra": {
        "autoload-splitter": {
            "splitter-logic": "WP_CLI\\AutoloadSplitter",
            "splitter-location": "php/WP_CLI/AutoloadSplitter.php",
            "split-target-prefix-true": "autoload_commands",
            "split-target-prefix-false": "autoload_framework"
        }
    }
}
```

After the next update/install, you will have both a `vendor/autoload_framework.php` and a `vendor/autoload_commands.php` file, that you can simply include and use to autoload the individual groups of classes as needed.

Valid "extra" Keys
------------------

You can configure the Autoload Splitter by providing `"extra"` keys under the `"autoload-splitter"` root key.

* __`"splitter-logic"`__ :

    Fully qualified class name of the class that contains the splitter logic.
    The class will be `__invoke()`d with two arguments, the fully qualified class name as well as the path to the class source file.

* __`"splitter-location"`__:

    Location of the splitter logic class source file. This is used to manually require the class file in case it was not available to the Composer plugin through autoloading.

* __`"split-target-prefix-true"`__ :

    Prefix that is used to generate the autoload files that contain the classes that evaluated to `true` through the splitter logic.

* __`"split-target-prefix-false"`__ :

    Prefix that is used to generate the autoload files that contain the classes that evaluated to `false` through the splitter logic.

Current Limitations
-------------------

### `"psr-0"` & `"psr-4"` autoloaders

To be able to work on individual classes, this Composer plugin will transform all PSR-0 and PSR-4 autoloaders into class maps for the split autoloading functionality. This is similar to the `--optimized` switch when generating standard autoloaders. During development, you will need to re-run the autoload generation after making changes to class names or locations, so that the split autoloaders are updated.

To re-generate the autoloaders, just run `composer dump-autoload` from within the project's root.

### `"files"` autoloaders

The `"files"` autoloading section is actually not a real autoloader. These files are eagerly included when the autoloader is triggered, and so they are simply ignored for the purposes of this Composer plugin.

License
-------

This code is released under the MIT license.

For the full copyright and license information, please view the [`LICENSE`](LICENSE) file distributed with this source code.
