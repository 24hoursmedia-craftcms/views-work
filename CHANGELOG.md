# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## [2.0.3.0] - 2023-10-22
### Fixed
- Improve postgresql compatibility (MR#83), thank you @ccchapman
- Updated node libs required for building assets

## [2.0.2.0] - 2023-3-25
### Fixed
- Fix Craft ErrorException when no signature is present (MR#94)

## [2.0.1.0] - 2022-09-26
### Fixed
- Craft 4 compatibility fix with many thanks to Wiejeben in https://github.com/24hoursmedia-craftcms/views-work/pull/91

## [1.3.0.7] - 2021-02-18
### Changed
- Minor changes and improvements

## [1.3.0.6] - 2021-02-18
### Changed
- Minor changes and improvements

## [1.3.0.5] - 2021-02-18
### Changed
- Minor changes and improvements

## [1.3.0.4] - 2021-02-18
### Changed
- Improved the dashboard for safari browsers

## [1.3.0.2] - 2021-02-18
### Changed
- Block registrations by cookies on a per-url basis
- Mention reset urls in Views Work dashboard

## [1.3.0.1] - 2021-02-18
### Added
* A new widget that shows content that is being currently viewed
* Exclude your own pageview registrations with a cookie
* Block bots from registrations
* Sort entries directly in the control panel by popularity with the sort drop down
* A dedicated Views Work dashboard with help to get you quickly get started
* Reset view counters daily with a free service like cronify.com instead of cron jobs
* Simplified twig programming api to retrieve popular content
* Programmatically increase view counters

## [1.2.6] - 2021-02-10
### Added
- Optionally allow GET requests to the reset url to allow easycron free service plan

## [1.2.5] - 2021-02-10
### Fixed
- Now uses action urls for reset instead of a named site route

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
