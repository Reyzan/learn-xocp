## Description

<!-- Provide a brief description of what this PR does -->

## Type of Change

<!-- Mark the relevant option with an "x" -->

- [ ] 🐛 Bug fix (non-breaking change which fixes an issue)
- [ ] ✨ New feature (non-breaking change which adds functionality)
- [ ] 💥 Breaking change (fix or feature that would cause existing functionality to not work as expected)
- [ ] 🔧 Modernization (refactoring to modern PHP practices)
- [ ] 📖 Documentation update
- [ ] 🎨 Code style/formatting
- [ ] ⚡ Performance improvement
- [ ] 🔒 Security fix
- [ ] 🧪 Test addition/update

## Related Issue(s)

<!-- Link to related issues using "Fixes #123" or "Closes #123" -->

Fixes #
Related to #

## Modernization Phase

<!-- If this PR is part of the modernization effort, which phase does it address? -->

- [ ] Phase 1: Foundation (PHP 7.4+, PDO, Sessions)
- [ ] Phase 1.5: PHP 8+ Migration (Enums, Readonly, Match, etc.)
- [ ] Phase 2: Security Hardening (Prepared statements, XSS, CSRF)
- [ ] Phase 3: Modern Architecture (PSR-4, DI, Routing)
- [ ] Phase 4: ORM/Query Builder
- [ ] Phase 5: Testing & CI/CD
- [ ] N/A - Not a modernization PR

## Changes Made

<!-- Describe the changes in detail -->

-
-
-

## PHP Version Compatibility

<!-- Which PHP versions have you tested this with? -->

- [ ] PHP 8.3
- [ ] PHP 8.2
- [ ] PHP 8.1
- [ ] PHP 8.0
- [ ] PHP 7.4
- [ ] Older versions (specify): ___

## Testing

<!-- Describe how you tested these changes -->

### Manual Testing
<!-- Describe manual testing steps -->

- [ ] Tested locally
- [ ] Tested on staging environment
- [ ] Tested with different PHP versions
- [ ] Tested with MySQL
- [ ] Tested with PostgreSQL

### Automated Testing
<!-- Mark what automated tests you've added/updated -->

- [ ] Added unit tests
- [ ] Added integration tests
- [ ] Updated existing tests
- [ ] All tests pass locally
- [ ] CI/CD pipeline passes

## Code Quality

<!-- Verify code quality -->

- [ ] Code follows PSR-12 coding standards
- [ ] PHPCS passes
- [ ] PHPStan analysis passes
- [ ] No deprecated functions used
- [ ] Added type hints where possible
- [ ] Added PHPDoc blocks where needed
- [ ] Security vulnerabilities addressed

## Database Changes

<!-- If this PR includes database changes -->

- [ ] Database migrations included
- [ ] Migration tested (up and down)
- [ ] Backward compatible
- [ ] Data migration script provided (if needed)

**Migration file(s):**
- `migrations/xxx_description.php`

## Security Considerations

<!-- Address security implications -->

- [ ] No SQL injection vulnerabilities
- [ ] XSS protection in place (using `escape()` helper)
- [ ] CSRF protection added for forms
- [ ] Input validation implemented
- [ ] Output escaping implemented
- [ ] Password hashing uses `password_hash()` (if applicable)
- [ ] No sensitive data in logs

## Breaking Changes

<!-- If this is a breaking change, describe the impact and migration path -->

**Breaking Changes:**
-

**Migration Guide:**
1.
2.
3.

## Screenshots/Recordings

<!-- If applicable, add screenshots or recordings to demonstrate the changes -->

## Checklist

<!-- Ensure you've completed all required items -->

- [ ] My code follows the project's coding standards
- [ ] I have performed a self-review of my code
- [ ] I have commented my code, particularly in hard-to-understand areas
- [ ] I have made corresponding changes to the documentation
- [ ] My changes generate no new warnings
- [ ] I have added tests that prove my fix is effective or that my feature works
- [ ] New and existing unit tests pass locally with my changes
- [ ] Any dependent changes have been merged and published
- [ ] I have updated the MODERNIZATION_GUIDE.md (if applicable)

## Modern PHP Features Used

<!-- If you used PHP 8+ features, list them here -->

- [ ] Named arguments
- [ ] Constructor property promotion
- [ ] Readonly properties/classes
- [ ] Enums
- [ ] Match expressions
- [ ] Nullsafe operator (`?->`)
- [ ] Union types
- [ ] Attributes
- [ ] String functions (`str_contains`, `str_starts_with`, etc.)

## Performance Impact

<!-- Describe any performance implications -->

- [ ] No performance impact
- [ ] Performance improved
- [ ] Performance may be affected (explain below)

**Performance notes:**


## Additional Notes

<!-- Any additional information that reviewers should know -->

## Reviewer Guidance

<!-- Help reviewers understand what to focus on -->

**Please review particularly:**
-
-

**Testing focus:**
-
-

---

<!--
For Claude Code PRs:
This PR was created with assistance from Claude Code.
All changes have been reviewed and tested.
-->
