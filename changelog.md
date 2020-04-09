# Changelog

All notable changes to this project will be documented in this file.

## 2020.04
* Updated: Certificate creation default date to meet new requirements from [Ballot 193](https://cabforum.org/2017/03/17/ballot-193-825-day-certificate-lifetimes/).
* Fixed: Uses a set version for Alpine in order to have a constant call to nslookup for docker.for.mac.localhost. 

## 2020.03
* Added: PHP rules for .editorconfig, including config for many PhpStorm rules
* Fixed: Broken composer.lock file preventing Gravity Forms installation

## 2020.02
* Updated: s3-uploads plugin to 2.2.1
* Changed: Refactored the image component to reduce complexity and allow more robust usage options and made a general pass at code clean up & documentation.
* Changed: all uses of `the_tribe_image()` ion the core plugin have been refactored to use the image component directly.
* Updated: Docs for Image Component, theme Images to reflect current architecture.
* Fixed: Removed almost all references to Grunt in the docs because it is switched to Gulp. The only references left are for the outdated videos.
* Added: Several helper methods to the Theme Colors class for working with color arrays.


## 2020.01
* Changed: Improve composer performance: Load hirak/prestissimo globally, volume mount entire composer dir, use "no-api": true, for VCS repositories, fix gravity forms installer.
* Fixed: Sidebar.php template from calling Base::get_data() needlessly in the same request
* Add: Added example eslint and phpcs github workflows
* Fixed: Typo in docker logs naming for NPM scripts. 

## 2019.12
* Changed: fixed main documentation links and updated WP CLI xdebug docs
* Changed: set wpx.sh to executable
* Updated: Node & NPM to latest LTS versions and all FE build tooling to latest (compatible) package versions. Related misc FE build tooling tweaks to accommodate new package versions.
* Changed: Removed postcss custom property path vars in favor of postcss-assets plugin because [custom properties are not supported in `url()`'s](https://stackoverflow.com/a/42331003/1135190) per the CSS spec.

## 2019.11
* Added `TRIBE_DISABLE_PANELS_CACHE` to `local-config.php`
* Removed: Google+ (deprecated) support for social sharing and following functionality
* Added container component to allow for more composition flexibility
* Added ifdef loader for Webpack to allow exclusion of React app chunk generation during main js bundle dev work
* Changed: update docker `start.sh` script to check for a root `.env` file
* Removed: SVG_Support Class and Util Provider
* Added: Plugin: Safe SVG
* Updated: WordPress to 5.2.4
* Updated: Plugin: gravity-forms-wcag-20-form-fields to 1.7.2
* Updated: Plugin: regenerate-thumbnails to 3.1.1
* Updated: Plugin: the-events-calendar to 4.9.10
* Updated: Plugin: wordpress-seo to 12.4
* Updated: Plugin: user-switching to 1.5.2
* Updated: Plugin: classic-editor-addon to 2.5.0
* Changed: added check to panels caching to avoid caching on panel preview

## 2019.10
* Changed: Updated core plugin to work with the Tribe Libs monorepo
* Changed: Loosened version constrain on Tribe Libs packages to "^2.0"

## 2019.09
* Changed: Cleaned up the `Page_Title` Class for readability and code standards

## 2019.08
* Fixed: Don't ignore sub folders called wp
* Changed: sq1 Twig classes to use non-deprecated Twig classes and bump twig minimum to v2.11

## 2019.07
* Updated: Lodash to 4.17.14
* Removed 3rd-party premium plugins, added composer installer for them
* Add direct 1password link in .env.sample
* Removed 3rd-party premium plugins, added composer installer for them
* Added "prefer-stable" to composer.json to resolve failing installs
* Re-organized docs. Added getting started docs for new folks

## 2019.06
* Fixed: Typo dash in modern-tribe removed from set remote in convert-project-to-fork.sh
* Changed: Updated convert-project-to-fork.sh to use ssh instead of https for setting remote url
* Add a utility for generating required pages
* Changed: Updated the Image Component and `the_tribe_image()` method to accept an image URL fallback
* Fixed: Restored returning an image size that is an exact match to the full size image, when that size is specifically requested.

## 2019.05
* Changed: Updated Classic Editor Plugins to latest Versions
* Updated: Swiper to version 4.5.0
* Changed: Added autoprefixer support for CSS Grid 
* Changed: Updated WordPress core to 5.1.1
* Changed: Updated ACF to 5.7.13
* Updated WordPress to 5.2.1
* Fixed: Tribe Branding plugin: updated deprecated `login_headertitle` filter to use `login_headertext` instead

## 2019.04

* Changed: Added npm commands for running codeception tests
* Changed: Added in more formal documentation and reasoning behind Lazy Loading classes in our Service Providers.
* Changed: Updated default PHP version to 7.2
* Changed: Added in default Panels Caching
* Changed: Added in Whoops library
* Add `WP_DISABLE_FATAL_ERROR_HANDLER` constant to wp-config to disable the fatal error handler

## 2019.03

* Updated to node 10.15.0
* Updated to use Gulp
* Updated to use Products linting standards for js, react and pcss
* Eslint and Stylelint now first attempt to fix all linting errors before running
* Added Bugsnag integration
* Added React app injection into main js bundle
* Added example react app
* Added wider alias support for common js directories
* Breaks up webpack into a modular setup
* Added webpack bundle analyzer reports
* Moves swiper to non webpack loading as it has issues with webpack.
* Changed: .travis.yml to properly run integration, acceptance and web driver tests and set a github token for dependencies
* Changed: .travis.yml to create cache directories and add memcached servers to local-config.php 
* Changed: tests-config-sample.php to disable admin SSL 
* Changed the documentation to mention "global" when it comes to the script for the certificates generation
* Added references to queues and schema to the docs general overview
* Added steps to follow on Linux environments to avoid problems with DNS Resolution when setting up the global Docker environment.

## 2019.02

* Add bcrypt password hashing: https://github.com/roots/wp-password-bcrypt
* Refactor force-plugin-activation.php to allow forcing plugins off when running unit tests
* Added tribe-chrome global container for chromedriver acceptance testing
* Redid codeception config and added sample acceptance and webdriver tests
* Change object-cache-sample.php to object-cache.php for default inclusion on forked projects
* Update incorrect global setting for memcached_server in local-config-sample.php

## 2019.01

* Added a meta importer CLI command.
* Fixed: Add sintax hightlight for docs with code examples
* Replaced the Pimple Dumper with a container exporter to work with recent versions of PhpStorm
* Fixed: Set explicit charset and collation for queues MySQL table
* Fixed: Handled invalid records in the queue table, avoiding a fatal error
* Modified: Test Suite configuration by moving it to the dev directory and consolidating composer management.

## 2018.12

* Added: caption position support to video component
* Fixed: gitignore entry for Tribe 301 plugin
* Added the Blog Copier
* Changed: added more documentation for our SQ1 forms implementation
* Fixed failing test for Full_Size_Gif

## 2018.11

* Fixed: Nginx config to properly pull missing assets (images/js/css/media etc...) from a remote server so you don't need to download large uploads folders.
* Changed: Cleaned up the `CLI_Provider`
* Changed: Introduced the `Generator_Command` abstract class for generator commands, so that we don't need all commands following the same constructor.
* Added: the following JavaScript unit tests: accessibility.test, apply-browser-classes.test, body-lock.test
* Fixed: Instructions to view the project logs inside docker
* Added: install guide for Ubuntu users on `docs/docker` documentation

## 2018.10

* Changed: Glomar now sets what looks like a logged in cookie to bypass Wp Engine's Varnish.
* Fixed: Autoplay for iframe video embeds in Chrome.
* Fixed: global start.sh script to properly get the docker IP address on linux

## 2018.09

* Added an incremental increase to the delay for reprocessing a failed task in the queue, based on the number of times it has failed.
* Changed: Docker setup docs
* Added: Alias docker scripts with `npm` for use in any project directory.
* Added: Fork project script: `dev/bin/convert-project-to-fork.sh`

## 2018.08

* Changed: Updated WordPress to 4.9.8
* Fixed: SVG_Support no longer throws errors in PHP 7.2
* Changed: Deferred get_content in Twig controller usage so various WordPress FE resources are loaded in the correct order
* Added: Object-meta documentation to add `nav_menus` and `nav_menus_items`
* Added: [`dev/docker/wpx.sh`](dev/docker/wpx.sh) WP CLI with xdebug script and updated [documentation](dev/docker/README.md).
* Added: PostCSS partials for the social share & follow components
* Fixed: Updated MediaElement styling for the embedded audio/video player
* Removed: PostCSS Lost Grid plugin and PostCSS settings
* Added: Classic Editor and Classic Editor Addon plugins.
* Added: Analytics ACF settings to the "General Settings" page with a GTM field and FE/theme integration.
* Added: the build process include in wp-config.php for FE asset cache busting.
* Added: postcss-preset-env to replace the now deprecated postcss-cssnext.
* Removed: deprecated postcss-cssnext
* Added: Theme/Full_Size_Gif - filters image_downsize to provide full size gifs only. It is commented out in the Theme service provider by default.

## 2018.07

* Added: social media ACF settings
* Added: social follow data to the base controller
* Changed: Updated Instagram and Google+ Icons
* Changed: Updated the Social Follow twig template
* Added: a new server_dist grunt task that excludes yarn installing
* Changed: Now updating build-process.php when performing any grunt compilation task to aid with performant deploys
* Changed: Updated WordPress to 4.9.7
* Added: Ansible boilerplate directory
* Removed: manifest.js enqueue from admin scripts. This was a remnant from the previous version of Webpack which was updated to v4.5.0 in v1.2.

## 2018.06
* Changed: JS slide util - Changed delay-based slide to RAF-based
* Added: Queues framework. This allows for running slower processes separately from a browser request.
  * Queue - A queue requires a backend for storing messages that passed to it.
  * Backends - MySQL is the default backend, but it is easy to implement the Backend interface and roll your own.
  * Messages (or Tasks) - Isolated functions that are run by the queue consumer.

## 2018.05

A whole lot of things have been added. The timing of when they were added and the corresponding versions would be too laborious to uncover. So...we'll start with some recent changes/additions.

* Added: Permissions Framework
* Added: TravisCI config file so tests get built when creating/updating a PR
* Added: Taxonomy List component
* Changed: Switched to composer for WP rather than submoduling
* Changed: Updated to webpack 4.5.0
* Changed: Disable `SCRIPT_DEBUG` by default, enable for gardens
* Changed: Remove GF WCAG and Regenerate plugins from version control
* Changed: Fixed issue with the install path for composer
* Changed: Synchronize VM time with system time when container starts
* Changed: Use new 1.1.1.1 DNS instead of Google's

## 2015.03–2018.04

* Long journey in the unversioned wilderness

## 2015.03

* Changed: Migrated to github, all history collapsed into the initial commit
* Added: Codeception test examples and instructions
* Changed: Moved the project dependencies to Composer

