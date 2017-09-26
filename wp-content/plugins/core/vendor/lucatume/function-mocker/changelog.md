# Change Log
All notable changes to this project will be documented in this file. This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased][unreleased]

## [1.3.7]
### Fixed
- issue that would generate en excpetion when providing an empty configuration to the `FunctionMocker::init` method

## [1.3.6]
### Fixed
- smaller PHPUnit `<= 6.0` incompatibilities

## [1.3.5]
### Fixed
- Patchwork library configuration creation to address caching issues

## [1.3.4]
### Fixed
- really fixed the issue with locating the `vendor` folder that would generate PHP notices (thanks @sciamannikoo)

## [1.3.3]
### Fixed
- an issue with locating the `vendor` folder that would generate PHP notices

## [1.3.2]
### Changed
- `phpunit/phpunit` required version to `>=5.7`

## [1.3.1]
### Added
- support to replace non defined functions in tests
- expose high level API as functions on the `tad\FunctionMocker` namespace

## [1.3.0]
### Added
- support for p[PhpUnit](https://phpunit.de/ "PHPUnit  The PHP Testing Framework")  version `6.0` (issue #4)

## [1.2.2]
### Fixed
- argument setting for closure return values

## [1.2.1]
### Added
- require `phpunit/phpunit` version `5.4`

## [1.2.0]
### Added 
- fallback caching path to the `init` method to avoid no caching in place at all
- support for internal function replacement (issue #3)

### Fixed
- hard-coded `vendor` path (issue #2)

### Removed
- support to replace non-defined functions

## [1.1.0] - 2016-06-03
### Added
- this changelog

### Changed
- updated `antecedent/patchwork` dependency to `1.5`
- updated `phpunit/phpunit` dependency to `5.4`

### Fixed
- issue where verifying the same instance replacement would result in double instance creations

[unreleased]: https://github.com/lucatume/function-mocker/compare/1.3.7...HEAD
[1.3.7]: https://github.com/lucatume/function-mocker/compare/1.3.6...1.3.7
[1.3.6]: https://github.com/lucatume/function-mocker/compare/1.3.5...1.3.6
[1.3.5]: https://github.com/lucatume/function-mocker/compare/1.3.4...1.3.5
[1.3.4]: https://github.com/lucatume/function-mocker/compare/1.3.3...1.3.4
[1.3.3]: https://github.com/lucatume/function-mocker/compare/1.3.2...1.3.3
[1.3.2]: https://github.com/lucatume/function-mocker/compare/1.3.1...1.3.2
[1.3.1]: https://github.com/lucatume/function-mocker/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/lucatume/function-mocker/compare/1.2.2...1.3.0
[1.2.2]: https://github.com/lucatume/function-mocker/compare/1.2.1...1.2.2
[1.2.1]: https://github.com/lucatume/function-mocker/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/lucatume/function-mocker/compare/1.1.0...1.2.0
[1.1.0]: https://github.com/lucatume/function-mocker/compare/1.0.5...1.1.0
