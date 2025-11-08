# .specify - Spec-Driven Development for XOCP Template Modernization

This directory contains specifications, plans, and task breakdowns for modernizing the XOCP template engine using spec-driven development principles.

## 📁 Directory Structure

```
.specify/
├── README.md           # This file
├── SPEC.md             # Full feature specification
├── PLAN.md             # Implementation plan & timeline
├── TASKS.md            # Detailed task breakdown
├── AGENT.md            # Claude Code configuration
└── diagrams/           # Architecture diagrams (TBD)
```

## 📖 Document Guide

### SPEC.md - Feature Specification
**Purpose:** Define WHAT we're building and WHY

**Contains:**
- User scenarios (P1, P2, P3)
- Functional requirements (FR-001 through FR-007)
- Success criteria (measurable outcomes)
- Edge cases and error scenarios
- Technical constraints
- Before/after comparisons

**Read this to understand:**
- The vision for the template engine
- User needs and pain points
- What success looks like

---

### PLAN.md - Implementation Plan
**Purpose:** Define HOW we'll build it

**Contains:**
- 4-week timeline
- Phase breakdown
- Architecture design
- Code examples
- Testing strategy
- Migration approach
- Risk mitigation

**Read this to understand:**
- The development approach
- Technical implementation details
- Testing and migration strategy

---

### TASKS.md - Task Breakdown
**Purpose:** Define specific TASKS to complete

**Contains:**
- 20 detailed tasks
- Estimates and dependencies
- Acceptance criteria
- Code snippets
- Status tracking

**Read this to understand:**
- What needs to be done
- Task priorities and estimates
- Current progress

---

### AGENT.md - Claude Code Configuration
**Purpose:** Configure Claude Code for this project

**Contains:**
- Project context
- Coding standards
- Common commands
- File patterns
- Testing instructions

**Read this to understand:**
- How to work with Claude Code on this project

---

## 🎯 Current Focus: Template Engine Modernization

**Goal:** Replace XocpHTML class with modern Laravel Blade-like template system

**Why:**
- Current system (2002): String concatenation, XSS vulnerabilities, unmaintainable
- New system: Clean templates, auto-escaping, inheritance, 70% less code

**Status:** Planning Phase

**Next Actions:**
1. Review SPEC.md
2. Approve PLAN.md
3. Start TASK-001 (Architecture Design)

---

## 🚀 Getting Started

### For Developers

1. **Read the spec first:**
   ```bash
   cat .specify/SPEC.md
   ```

2. **Review the plan:**
   ```bash
   cat .specify/PLAN.md
   ```

3. **Check current tasks:**
   ```bash
   cat .specify/TASKS.md | grep "📋 Todo" -A 5
   ```

4. **Work with Claude Code:**
   ```bash
   # Claude will read AGENT.md automatically
   # Just start coding!
   ```

### For Project Managers

1. **Track progress:**
   - Check TASKS.md daily
   - Review task status updates
   - Monitor blockers

2. **Review deliverables:**
   - Week 1: Prototype
   - Week 2: Full features
   - Week 3: Integration
   - Week 4: Migration

3. **Measure success:**
   - See "Success Criteria" in SPEC.md
   - 70% code reduction
   - 100% XSS protection
   - Performance maintained

---

## 📊 Quick Stats

**Project:** Template Engine Modernization
**Duration:** 4 weeks
**Tasks:** 20
**Team:** Claude Code + Human Developers

**Completion:**
- ✅ Specification: 100%
- ✅ Planning: 100%
- ⏳ Implementation: 0%
- ⏳ Testing: 0%
- ⏳ Documentation: 0%

---

## 🔗 Related Files

**Modernization Guide:**
- See `MODERNIZATION_GUIDE.md` in repo root
- Template engine is part of Phase 3 (Architecture)

**GitHub Spec Kit:**
- See `GITHUB_SETUP.md` for CI/CD
- CI will run tests for template engine

**Code Location:**
- Current: `class/xocphtml.php` (legacy)
- New: `src/View/` (to be created)
- Tests: `tests/Unit/View/` (to be created)

---

## 💡 Principles

This project follows spec-driven development:

1. **Spec First:** Define requirements before coding
2. **User-Centered:** Start with user scenarios
3. **Testable:** Each feature independently testable
4. **Measurable:** Clear success criteria
5. **Iterative:** Review and refine

---

## 🆘 Need Help?

**Questions about the spec?**
- Review SPEC.md section: "Open Questions"
- Post in GitHub Discussions

**Questions about implementation?**
- Check PLAN.md section: "Technical Implementation"
- Ask Claude Code for guidance

**Blocked on a task?**
- Update TASKS.md with ❌ Blocked status
- Note the blocker reason
- Escalate to team lead

---

## 📝 How to Update

### When starting a task:
```bash
# Update TASKS.md
# Change task status from 📋 Todo to 🏃 In Progress
```

### When completing a task:
```bash
# Update TASKS.md
# Change status to ✅ Done
# Update completion stats in this README
```

### When spec changes:
```bash
# Update SPEC.md
# Increment version number
# Note changes in changelog
# Update affected tasks in TASKS.md
```

---

## 🎉 Success Indicators

You'll know this is working when:
- ✅ Developers prefer writing templates over PHP strings
- ✅ Code reviews mention improved readability
- ✅ XSS vulnerabilities drop to zero
- ✅ New features ship faster
- ✅ Themes are easier to customize

---

## 📅 Timeline

```
Week 1: Jan 8-14   → Foundation & Prototype
Week 2: Jan 15-21  → Directives & Features
Week 3: Jan 22-28  → Integration & Security
Week 4: Jan 29-Feb 4 → Migration & Documentation
```

---

**Created:** 2025-01-08
**Last Updated:** 2025-01-08
**Status:** Active Development
**Owner:** Claude Code + XOCP Team

---

*This .specify folder is part of GitHub's spec-kit initiative for spec-driven development. Learn more: https://github.com/github/spec-kit*
