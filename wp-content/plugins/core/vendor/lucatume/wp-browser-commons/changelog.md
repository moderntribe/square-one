# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased][unreleased]

## [1.2.8.2] - 2017-01-25
### Fixed
- execution and output in `Executor:realExec()` method

## [1.2.8.1] - 2016-10-31
### Added
- `WP::do_action` method
- `WP::apply_filters` method
- `WP::WP_CONTENT_DIR` method

## [1.2.8] - 2016-10-31
### Added
- `Utils::recurseRemoveDir` method

## [1.2.7] - 2016-10-27
### Added
- `WP::switch_theme` method

## [1.2.6] - 2016-09-05
### Added
- `execAndOutput` method to `Environment\Executor` class

## [1.2.5] - 2016-09-02
### Addded
- `Executor` class to abstract process handling.

## [1.2.4] - 2016-08-19
### Added
- `WP::set_site_transient` method

## [1.2.3] - 2016-08-10
### Added
- `Filesystem::is_readable` method

## [1.2.2] - 2016-06-09
### Added
- the `WP` adapter module

## [1.2.1] - 2016-06-09
### Changed
- the `Bootstrapper` nonce generation method to use browser credentials

## [1.2.0] - 2016-06-08
### Added
- `Bootstrapper`  class to handle bootstrapping a WordPress instance to send it requests

## [1.1.3] - 2016-06-06
### Fixed
- `Filesystem::unlinkDir` variable name

## [1.1.2] - 2016-06-06
### Changed
- the `Filesystem::unlinkDir` does not rely on external functions

## [1.1.1] - 2016-06-06
### Added
- `unlinkDir` method to the `Filesystem` class

## [1.1.0] - 2016-05-25
### Added
- the `Constants` wrapper class

## [1.0.1] - 2016-05-20
### Fixed
- method name typo

## [1.0.0] - 2016-05-19
### Added
- Initial commit.

[unreleased]: https://github.com/lucatume/wp-browser-commons/compare/1.2.8.2...HEAD
[1.2.8.2]: https://github.com/lucatume/wp-browser-commons/compare/1.2.8.1...1.2.8.2
[1.2.8.1]: https://github.com/lucatume/wp-browser-commons/compare/1.2.8...1.2.8.1
[1.2.8]: https://github.com/lucatume/wp-browser-commons/compare/1.2.7...1.2.8
[1.2.7]: https://github.com/lucatume/wp-browser-commons/compare/1.2.6...1.2.7
[1.2.6]: https://github.com/lucatume/wp-browser-commons/compare/1.2.5...1.2.6
[1.2.5]: https://github.com/lucatume/wp-browser-commons/compare/1.2.4...1.2.5
[1.2.4]: https://github.com/lucatume/wp-browser-commons/compare/1.2.3...1.2.4
[1.2.3]: https://github.com/lucatume/wp-browser-commons/compare/1.2.2...1.2.3
[1.2.2]: https://github.com/lucatume/wp-browser-commons/compare/1.2.1...1.2.2
[1.2.1]: https://github.com/lucatume/wp-browser-commons/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/lucatume/wp-browser-commons/compare/1.1.3...1.2.0
[1.1.3]: https://github.com/lucatume/wp-browser-commons/compare/1.1.2...1.1.3
[1.1.2]: https://github.com/lucatume/wp-browser-commons/compare/1.1.1...1.1.2
[1.1.1]: https://github.com/lucatume/wp-browser-commons/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/lucatume/wp-browser-commons/compare/1.0.1...1.1.0
[1.0.1]: https://github.com/lucatume/wp-browser-commons/compare/1.0.0...1.0.1

