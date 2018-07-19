# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [1.5] - TBD

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
