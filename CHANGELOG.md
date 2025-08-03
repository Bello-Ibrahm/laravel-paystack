# Changelog

All Notable changes to `laravel-paystack` will be documented in this file

The format is inspired by [Keep a Changelog](https://keepachangelog.com) and this project adheres to [Semantic Versioning](https://semver.org).

---

## [Unreleased]

### Added
- Created `PaystackClient` class to abstract Guzzle HTTP requests.
- Introduced service classes: `TransactionService`, `CustomerService`, `PlanService`, etc., each handling a specific Paystack domain.
- Added unit test, and integration test for `TransactionService` using real API keys (with backup mocking planned).
- Improved exception handling in Paystack services
- Created `.phpunit.result.cache` for PHPUnit test caching.
- Added docblocks to service classes and methods for improved IDE support and maintainability.
- Added `setup.sh` to automate initial project setup


### Changed
- Refactored `Paystack.php` to delegate to service classes, following the Single Responsibility Principle (SRP).
- Composer autoload structure improved for better PSR-4 compliance.
- Improved test folder and autoload mappings.
- Improved `CHANGELOG.md` for release history tracking.
- Improved folder structuring eg `resources/config/paystack.php` to `config/paystack.php`

### Fixed
- Removed deprecated test code

### Removed
- Removed unused service logic from the core `Paystack` class.
- Cleaned up old/unused config entries.

---

## 2020-05-23

### Added
- Support for Laravel 7
- Support for splitting payments into subaccounts
- Support for more than one currency. Now you can use USD!
- Support for multiple quantities
- Support for helpers

---

## 2015-11-04

### Added
- Initial release
