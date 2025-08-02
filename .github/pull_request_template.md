# 🚀 Pull Request

## Summary

<!-- Describe the change you're introducing -->
Refactor to enforce Single Responsibility Principle (SRP) in `PaystackClient`. Extracted responsibilities into dedicated service classes.

## 🔍 Related Issues

<!-- e.g., Fixes #12, Closes #45 -->
Fixes #<!-- issue number -->

## ✅ Changes Made

- [x] Extracted HTTP logic into `PaystackClient`
- [x] Created `TransactionService`, `CustomerService`, etc.
- [x] Applied PSR-4 autoloading
- [x] Added phpDocumentor support
- [x] Improved test structure

## 💡 Motivation

<!-- Why is this change needed? -->
Improving maintainability, testability, and code organization of the SDK.

## 🧪 Tests

<!-- What did you test or automate? -->
- Existing unit tests pass ✅
- Added new tests for each service class

## 📋 Checklist

- [ ] Code builds without errors
- [ ] Tests pass locally
- [ ] Linted with `php-cs-fixer` or similar
- [ ] Documentation updated (if applicable)
- [ ] New classes follow SRP and SOLID

---

> _Please review this PR and leave feedback if needed._  
