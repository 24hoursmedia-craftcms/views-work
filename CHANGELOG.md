# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## [1.2.5] - 2021-02-10
### Fixed
- now uses action urls for reset instead of a named site route

## [1.2.4] - 2021-02-10
### Fixed
- Fixed an error where the CP became unreachable when admin changes are prohibited

## [1.2.3] - 2021-02-10
### Added
- Daily/weekly/monthly view counters can be reset with a special url if you do not have access to cron jobs.

### Modified
- Secret keys are now generated at plugin install (instead of after saving settings).

## [1.1.12] - 2020-10-27
### Fixes
- compatible with composer v2

## [1.1.8] - 2020-04-05
### Modified
- Modified logo

## [1.1.7] - 2020-02-26
### Modified
- secret signing keys are now automatically generated in settings if the field is left empty

## [1.0.5] - 2020-02-26
### Fixed
- fixes db installation issue

## [1.0.1] - 2020-02-26
### Fixed
- adds extra db field for tracking weekly views


## [1.0.0] - 2020-02-26
### Added
- Initial release
