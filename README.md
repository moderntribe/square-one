#  Tribe Square One ([1]) Development Framework


[1] is a base development framework used by Modern Tribe when kicking off new WordPress projects. It contains a base for:

* Our build process (node, grunt & bower) | /dev
* Theme independent core functionality plugin | /content/mu-plugins
* Core plugins (used on every project) | /content/plugins
* Theme (templates, markup & structure) | /content/themes/tribe-square-one
* Front-end asset structure (JS, SCSS & CSS)


## Getting Started


To begin using [1]:

1. Change the name of our WordPress theme from *tribe-square-one* to *tribe-clientname*
2. Update the information in: *content/themes/tribe-clientname/style.css*
3. Update the constant: *WP_DEFAULT_THEME* in *wp-config.php* to the updated theme name
4. Update the variable *_themepath* found in: */dev/package.json* to be: *content/themes/tribe-clientname*

#### Using the build tools

The build tools for themeing can be found in the */dev/* directory. Refer to its README for instructions on getting started with Grunt, Bower, and SASS.

*NOTE: One helpful tip, all relevant directories will contain a readme outlining the directory contents as well as any relevant information, tips, and instructions.*


## Configurations


*Production*

When using this on a production environment, be sure to add the following to your local-config.php:
    define( 'ENVIRONMENT', 'PRODUCTION' );

*Development*

GLOMAR is a plugin that blocks the frontend of the site from public access. If you would like to disable the plugin locally, add the following to your local-config.php:
    define( 'TRIBE_GLOMAR', false );


## Upgrade Notice


= 1.0 =
Initial Release


## Changelog


= 1.0 =
Initial Release

