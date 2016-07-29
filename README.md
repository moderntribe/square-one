#  Tribe Square One ([1]) Development Framework


[1] is a development framework created by Modern Tribe for use in our WordPress projects. It contains a base for:

* Our build process (node, grunt, webpack)
* Theme independent core functionality plugin | /content/mu-plugins
* Core plugin | /content/plugins/core
* Core theme | /content/themes/core
* Front-end asset structure (JS, PostCSS & CSS)

## Table of Contents

* Guides
  * [Node](/docs/guides/node.md)
* Theme
  * [PostCSS](/docs/theme/postcss.md)
  * [Javascript](/docs/theme/javascript.md)
  * [Images](/docs/theme/images.md)
  * [Fonts](/docs/theme/fonts.md)
  * [Icons](/docs/theme/icons.md)
* Tests and Code Quality
  * [Codeception](/docs/tests/codeception.md)



#### Using the build tools

The build tools for themeing can be found in the *root* directory. Refer to the docs for instructions on getting started with Grunt, Webpack, and PostCSS.


## Configurations


*Production*

When using this on a production environment, be sure to add the following to your local-config.php:
    define( 'ENVIRONMENT', 'PRODUCTION' );

*Development*

GLOMAR is a plugin that blocks the frontend of the site from public access. If you would like to disable the plugin locally, add the following to your local-config.php:
    define( 'TRIBE_GLOMAR', false );


## Upgrade Notice

= 1.1 =
Moved the project dependencies to Composer  
Added Codeception tests examples and instructions  
= 1.0 =
Initial Release


## Changelog


= 1.1 =
Moved the project dependencies to Composer  
Added Codeception tests examples and instructions  
= 1.0 =
Initial Release

