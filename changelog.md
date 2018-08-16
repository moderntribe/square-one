# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [1.7.1] - 2018-08-14

* Added [`dev/docker/wpx.sh`](dev/docker/wpx.sh) WP CLI with xdebug script and updated [documentation](dev/docker/README.md).

## [1.7] - 2018-08-13

### Added

* Added in the Classic Editor and Classic Editor Addon plugins.
* Added Analytics ACF settings to the "General Settings" page with a GTM field and FE/theme integration.
* Added the build process include in wp-config.php for FE asset cache busting.

### Changed

Replaced deprecated postcss-cssnext with postcss-preset-env.

## [1.6] - 2018-08-01

Functionality was added to allow a project to require all gifs displayed anywhere at full size and not a thumbnail.  It is commented out in the Theme service provider by default.

### Added

* Theme/Full_Size_Gif - filters image_downsize to provide full size gifs only

## [1.5.3] - 2018-07-30

### Added

* Added social media ACF settings
* Added the social follow data to the base controller

### Changed

* Updated Instagram and Google+ Icons
* Updated the Social Follow twig template

## [1.5.2] - 2018-07-23

### Added

* Added a new server_dist grunt task that excludes yarn installing

### Changed

* Now updating build-process.php when performing any grunt compilation task to aid with performant deploys

## [1.5.1] - 2018-07-22

### Changed

* Updated WordPress to 4.9.7

## [1.5] - 2018-07-20

### Added

* Ansible boilerplate directory

## [1.4.1] - 2018-07-12

### Fixed
* Removed manifest.js enqueue from admin scripts. This was a remnant from the previous version of Webpack which was updated to v4.5.0 in v1.2.

## [1.4] - 2018-06-19

### Changed

* JS slide util - Changed delay-based slide to RAF-based

## [1.3] - 2018-06-12

Queues framework has been added. This allows for running slower processes separately from a browser request.

### Added

* Queue - A queue requires a backend for storing messages that passed to it.
* Backends - MySQL is the default backend, but it is easy to implement the Backend interface and roll your own.
* Messages (or Tasks) - Isolated functions that are run by the queue consumer.

## [1.2] - 2018-05-08

A whole lot of things have been added. The timing of when they were added and the corresponding versions would be too laborious to uncover. So...we'll start with 1.2. Here are some recent changes/additions.

### Added

* Permissions Framework
* TravisCI config file so tests get built when creating/updating a PR
* Taxonomy List component

### Changed

* Switched to composer for WP rather than submoduling
* Updated to webpack 4.5.0
* Disable `SCRIPT_DEBUG` by default, enable for gardens
* Remove GF WCAG and Regenerate plugins from version control
* Fixed issue with the install path for composer
* Synchronize VM time with system time when container starts
* Use new 1.1.1.1 DNS instead of Google's

## [1.1] - 2015-03-31

### Added

* Added Codeception test examples and instructions

### Changed

* Moved the project dependencies to Composer

## [1.0] - ??

### Added

* Initial version
