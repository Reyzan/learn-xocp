# GitHub Repository Setup Guide

This guide explains how to set up and use the GitHub features included in this repository.

## 📋 Table of Contents

- [GitHub Actions CI/CD](#github-actions-cicd)
- [Issue Templates](#issue-templates)
- [Pull Request Template](#pull-request-template)
- [Dependabot](#dependabot)
- [Branch Protection](#branch-protection)
- [Repository Settings](#repository-settings)
- [Claude Code Integration](#claude-code-integration)

---

## 🚀 GitHub Actions CI/CD

### Overview

The repository includes a comprehensive CI/CD workflow (`.github/workflows/ci.yml`) that automatically:

- **Tests** your code on PHP 8.1, 8.2, and 8.3
- **Checks code quality** with PHPCS and PHPStan
- **Scans for security vulnerabilities** in dependencies
- **Verifies modernization compatibility** (checks for deprecated functions)
- **Generates build reports** with useful information

### Workflow Jobs

#### 1. Test Matrix (`test`)
Runs on every push and pull request:
- Tests on PHP 8.1, 8.2, 8.3
- Spins up MySQL 8.0 for integration tests
- Runs PHPUnit tests with code coverage
- Uploads coverage to Codecov

#### 2. Code Quality (`code-quality`)
- Runs PHP_CodeSniffer for style violations
- Runs PHPStan for static analysis
- Runs `composer audit` for security vulnerabilities

#### 3. Modernization Check (`modernization-check`)
- Scans for deprecated `mysql_*` functions
- Checks for old `session_register()` calls
- Looks for legacy superglobals (`$HTTP_GET_VARS`, etc.)
- Provides helpful links to MODERNIZATION_GUIDE.md

#### 4. Build Info (`build-info`)
- Generates build summary
- Comments on Claude-generated branches

### Setup Instructions

1. **Enable GitHub Actions**
   - Go to your repository Settings → Actions → General
   - Enable "Allow all actions and reusable workflows"

2. **Add Repository Secrets** (optional)
   - For Codecov integration: Add `CODECOV_TOKEN`
   - Settings → Secrets and variables → Actions → New repository secret

3. **Configure Branch Protection**
   - Settings → Branches → Add rule
   - Branch name pattern: `main`
   - Enable: "Require status checks to pass before merging"
   - Select: `test`, `code-quality`

### Workflow Triggers

The CI runs on:
- Push to `main`, `develop`, or any `claude/**` branch
- Pull requests to `main` or `develop`

### Viewing Results

- Go to **Actions** tab in your repository
- Click on a workflow run to see detailed results
- Each job shows logs and step-by-step output

### Example Output

```
✅ Test PHP 8.1 - Passed
✅ Test PHP 8.2 - Passed
✅ Test PHP 8.3 - Passed
✅ Code Quality - Passed
✅ Modernization Check - No deprecated functions found!
```

---

## 📝 Issue Templates

The repository includes three specialized issue templates:

### 1. 🐛 Bug Report (`bug_report.yml`)

**Use for:** Reporting bugs or unexpected behavior

**Fields:**
- Bug description
- Steps to reproduce
- Expected vs actual behavior
- PHP version
- Database type
- Modernization status
- Error logs
- Additional context

**How to use:**
1. Go to Issues → New Issue
2. Select "Bug Report"
3. Fill out the form
4. Submit

### 2. ✨ Feature Request (`feature_request.yml`)

**Use for:** Suggesting new features or enhancements

**Fields:**
- Feature type (module, API, UI, etc.)
- Problem statement
- Proposed solution
- Alternatives considered
- Modern PHP approach preference
- Example code
- Priority level
- Willingness to implement

**How to use:**
1. Go to Issues → New Issue
2. Select "Feature Request"
3. Describe your idea
4. Submit

### 3. 🔧 Modernization Help (`modernization_help.yml`)

**Use for:** Getting help with modernization

**Fields:**
- Which phase you're working on
- Current and target PHP versions
- Issue description
- What you've tried
- Code samples
- Timeline urgency
- Experience level
- Environment details
- Modernization path

**How to use:**
1. Go to Issues → New Issue
2. Select "Modernization Help"
3. Provide details about your issue
4. Submit for community assistance

### Issue Template Configuration

The `config.yml` file provides quick links to:
- 📖 Modernization Guide
- 💬 Discussions
- 🐛 Security advisories

---

## 🔄 Pull Request Template

### Overview

The PR template (`.github/PULL_REQUEST_TEMPLATE.md`) ensures all PRs include necessary information.

### Template Sections

1. **Description** - What does this PR do?
2. **Type of Change** - Bug fix, feature, modernization, etc.
3. **Related Issues** - Links to related issues
4. **Modernization Phase** - Which phase does this address?
5. **Changes Made** - Detailed list of changes
6. **PHP Compatibility** - Tested versions
7. **Testing** - Manual and automated testing
8. **Code Quality** - Standards compliance
9. **Database Changes** - Migrations included
10. **Security Considerations** - Security review
11. **Breaking Changes** - Migration guide if needed
12. **Screenshots** - Visual changes
13. **Checklist** - Pre-merge verification
14. **Modern PHP Features** - PHP 8+ features used
15. **Performance Impact** - Performance notes

### Best Practices

When creating a PR:

1. **Fill out all relevant sections** - Don't skip sections
2. **Link related issues** - Use "Fixes #123" or "Closes #456"
3. **Test thoroughly** - Check all PHP versions if possible
4. **Run CI locally** - Use `composer test` before pushing
5. **Update docs** - Keep MODERNIZATION_GUIDE.md updated
6. **Add tests** - Include unit/integration tests
7. **Check security** - Review for SQL injection, XSS, CSRF

### Example PR Title Formats

```
fix: Resolve SQL injection in user login
feat: Add PHP 8.1 enum support for user roles
refactor: Migrate XocpUser to use PDO prepared statements
docs: Update Phase 1.5 with PHP 8.3 features
perf: Optimize query builder with better caching
security: Add CSRF protection to all forms
```

---

## 🤖 Dependabot

### Overview

Dependabot (`.github/dependabot.yml`) automatically:
- Updates Composer dependencies weekly
- Updates GitHub Actions weekly
- Groups related updates together
- Ignores major version bumps (for stability)

### Configuration

**Composer Updates:**
- Schedule: Every Monday at 9:00 AM
- Max PRs: 10 concurrent
- Groups: Development vs Production dependencies
- Auto-labels: `dependencies`, `composer`

**GitHub Actions Updates:**
- Schedule: Every Monday at 9:00 AM
- Max PRs: 5 concurrent
- Auto-labels: `dependencies`, `github-actions`

### Managing Dependabot PRs

1. **Review the PR** - Check what's being updated
2. **Review changelog** - Click on release notes link
3. **Check CI** - Ensure all tests pass
4. **Merge if safe** - Merge patch/minor updates
5. **Test major updates** - Test thoroughly in staging

### Customizing Dependabot

Edit `.github/dependabot.yml`:

```yaml
# Change schedule
schedule:
  interval: "daily" # or "weekly", "monthly"

# Ignore specific packages
ignore:
  - dependency-name: "vendor/package"
    update-types: ["version-update:semver-major"]
```

---

## 🛡️ Branch Protection

### Recommended Settings

**For `main` branch:**

1. Go to Settings → Branches → Add rule
2. Branch name pattern: `main`
3. Enable the following:

**Protect matching branches:**
- ✅ Require a pull request before merging
  - ✅ Require approvals: 1
  - ✅ Dismiss stale pull request approvals
- ✅ Require status checks to pass before merging
  - ✅ Require branches to be up to date
  - Select: `test (8.1)`, `test (8.2)`, `test (8.3)`, `code-quality`
- ✅ Require conversation resolution before merging
- ✅ Require signed commits (optional)
- ✅ Include administrators (recommended)

**Rules applied to everyone including administrators:**
- ✅ Restrict who can push to matching branches
  - Add: Maintainers, Admins only

**For `develop` branch:**

Same as `main`, but allow:
- Direct pushes from trusted developers
- Fewer required approvals (0-1)

**For `claude/**` branches:**

No protection needed (temporary feature branches)

---

## ⚙️ Repository Settings

### General Settings

**Go to Settings → General**

**Features to enable:**
- ✅ Issues
- ✅ Projects (for roadmap tracking)
- ✅ Discussions (for Q&A)
- ✅ Preserve this repository (for important projects)

**Pull Requests:**
- ✅ Allow squash merging (recommended)
- ✅ Allow auto-merge
- ✅ Automatically delete head branches
- ✅ Suggest updating pull request branches

### Security Settings

**Go to Settings → Security**

**Dependency graph:**
- ✅ Enable

**Dependabot alerts:**
- ✅ Enable

**Dependabot security updates:**
- ✅ Enable

**Code scanning:**
- ✅ Enable (optional, for advanced security)

### Notifications

**Go to Settings → Notifications**

Customize notifications for:
- CI/CD failures
- Dependabot alerts
- Security advisories
- PR reviews

---

## 🤖 Claude Code Integration

### What is Claude Code?

Claude Code is an AI-powered coding assistant that can:
- Generate modernization code
- Create pull requests
- Review code
- Suggest improvements
- Write tests

### Using Claude Code with This Repository

1. **Clone the repository**
   ```bash
   git clone https://github.com/Reyzan/learn-xocp.git
   cd learn-xocp
   ```

2. **Create a Claude branch**
   ```bash
   git checkout -b claude/your-feature-name
   ```

3. **Work with Claude Code**
   - Claude will read the MODERNIZATION_GUIDE.md
   - Claude will follow the PR template
   - Claude will write tests
   - Claude will commit with proper messages

4. **Review Claude's work**
   - Always review generated code
   - Test thoroughly
   - Ensure security best practices

5. **Create PR from Claude branch**
   - Push to `claude/**` branch
   - CI will run automatically
   - Create PR when ready

### Claude Code Workflow Recognition

The CI workflow automatically detects Claude branches:
- Branches starting with `claude/`
- Adds special build information
- Comments on commits with CI results
- Provides quick links to documentation

### Example Claude Code Session

```
You: "Migrate XocpUser class to use PDO with PHP 8.1 features"

Claude: "I'll modernize XocpUser class:
1. Replace mysql_* with PDO
2. Add constructor property promotion
3. Add type hints
4. Use readonly properties
5. Add prepared statements
6. Write tests
Let me start..."

[Claude creates code, commits, and pushes to claude/modernize-xocpuser]

You: "Create a PR"

Claude: "I'll create a PR with:
- Detailed description
- Testing notes
- Migration guide
- Links to MODERNIZATION_GUIDE.md Phase 1"
```

---

## 📊 Project Boards (Optional)

### Setting Up a Modernization Board

1. Go to **Projects** → New project
2. Choose "Board" layout
3. Create columns:
   - 📋 Backlog
   - 🔍 Planning
   - 🏗️ Phase 1: Foundation
   - 🚀 Phase 1.5: PHP 8+
   - 🔒 Phase 2: Security
   - 🏛️ Phase 3: Architecture
   - 💾 Phase 4: ORM
   - 🧪 Phase 5: Testing
   - ✅ Done

4. Add issues to track modernization progress

### Automation

Enable automation:
- Auto-move issues to "Done" when closed
- Auto-move PRs to "In Progress" when opened
- Auto-archive cards after 14 days

---

## 🎯 Quick Start Checklist

Use this checklist to set up your repository:

- [ ] Enable GitHub Actions
- [ ] Configure branch protection for `main`
- [ ] Enable Dependabot alerts
- [ ] Enable Dependabot security updates
- [ ] Enable Discussions (optional)
- [ ] Create a project board (optional)
- [ ] Add team members as collaborators
- [ ] Configure notification preferences
- [ ] Add Codecov token (if using coverage)
- [ ] Test CI by creating a test PR
- [ ] Review and customize issue templates
- [ ] Review and customize PR template
- [ ] Star the repository for visibility

---

## 📚 Additional Resources

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [Dependabot Documentation](https://docs.github.com/en/code-security/dependabot)
- [Branch Protection Rules](https://docs.github.com/en/repositories/configuring-branches-and-merges-in-your-repository/defining-the-mergeability-of-pull-requests/about-protected-branches)
- [Issue Templates](https://docs.github.com/en/communities/using-templates-to-encourage-useful-issues-and-pull-requests)
- [Claude Code Documentation](https://docs.anthropic.com/claude/docs)

---

## 🆘 Troubleshooting

### CI is not running

**Check:**
1. Is GitHub Actions enabled? (Settings → Actions)
2. Is the workflow file valid YAML? (Use a YAML validator)
3. Are there any secrets required? (Check workflow file)

### Tests are failing

**Steps:**
1. Run tests locally: `vendor/bin/phpunit`
2. Check PHP version compatibility
3. Ensure database is running
4. Check .env configuration
5. Review test logs in Actions tab

### Dependabot PRs not appearing

**Check:**
1. Is Dependabot enabled? (Settings → Security)
2. Is `composer.json` valid?
3. Are there any updates available?
4. Check Dependabot logs (Insights → Dependency graph → Dependabot)

### Branch protection blocking merges

**Solutions:**
1. Ensure all required checks pass
2. Get required approvals
3. Resolve all conversations
4. Update branch with latest `main`

---

## 🤝 Contributing

See the [PR template](.github/PULL_REQUEST_TEMPLATE.md) for contribution guidelines.

For modernization help, see [MODERNIZATION_GUIDE.md](MODERNIZATION_GUIDE.md).

---

**Built with ❤️ using Claude Code**
