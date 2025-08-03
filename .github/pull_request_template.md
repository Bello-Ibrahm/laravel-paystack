# 🚀 Pull Request

## Summary

<!-- Describe the change you're introducing -->
Refactor to enforce Single Responsibility Principle (SRP) in `PaystackClient`. Extracted responsibilities into dedicated service classes.

---

## 🔍 Related Issues

<!-- e.g., Fixes #12, Closes #45 -->
Fixes #<!-- issue number -->

---

## ✅ Changes Made

- [x] Extracted HTTP logic into `PaystackClient`
- [x] Created `TransactionService`, `CustomerService`, etc.
- [x] Applied PSR-4 autoloading structure
- [x] Added docblocks for IDE
- [x] Improved test folder and structure

---

## 💡 Motivation

<!-- Why is this change needed? -->
Improving maintainability, testability, and adherence to clean architecture (SRP/SOLID principles).

---

## 🧪 Tests

<!-- What did you test or automate? -->
- [x] Unit tests for all new service classes
- [x] Integration tests using live API keys
- [x] Fallbacks to HTTP fake/mocks planned

---

## 📋 Checklist

- [x] Code builds without errors
- [x] All PHPUnit tests pass
- [x] Linted with `php-cs-fixer` or similar
- [x] Documentation updated (if applicable)
- [x] SRP principles respected across all services
- [x] PR title and description are clear

---

## 📸 Screenshots (UI-related changes only)

<!-- Add before/after screenshots if applicable -->

---

## ❗ Breaking Changes?

- [ ] Yes
- [x] No

<!-- If yes, describe what's breaking and how to migrate -->

---

## 💬 Additional Notes

<!-- Anything else the reviewers should know? -->

> _Please review this PR and provide feedback if needed._
