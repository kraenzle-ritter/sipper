# Changelog

All notable changes to `sipper` will be documented in this file

## 1.1.0 - 2025-08-05

### Added

- New method `getDokumentTitelByFilename()` to get document title by filename
- Comprehensive PHPUnit test suite with 15 tests covering all functionality
- Exception handling for `getDokumentByDateiRef()` and `getDateiByDateiRef()` methods
- PHPUnit configuration file for proper test execution
- Enhanced README with detailed usage examples and API documentation
- Updated GitHub Actions workflows for PHP 7.4-8.3 compatibility
- Added code quality workflow with syntax checking and test coverage

### Changed

- Improved error handling: methods now throw `InvalidArgumentException` when elements are not found
- Updated README from German to English with comprehensive documentation
- Test comments translated from German to English
- Updated GitHub Actions workflow from `master` to `main` branch
- Removed Laravel-specific dependencies from CI workflow
- Added support for PHP 8.1, 8.2, and 8.3 in CI pipeline

### Fixed

- Array access errors when XPath queries return empty results
- Proper namespace registration for XML element operations

## 1.0.0 - 2023-05-06

- initial release
