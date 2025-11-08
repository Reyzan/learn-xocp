# Template Engine Modernization - Feature Specification

**Feature:** Modern Template Engine for XOCP
**Target:** Replace XocpHTML with Laravel Blade-like templating
**Status:** Planning
**Version:** 1.0
**Date:** 2025-01-08

---

## 🎯 Executive Summary

Transform XOCP's outdated XocpHTML class (2002) into a modern, extensible template engine similar to Laravel Blade or CodeIgniter views, enabling easier theme customization, better maintainability, and cleaner code separation.

---

## 👥 User Scenarios (Prioritized)

### P1: Theme Developer Creating Custom Layouts
**As a** theme developer
**I want to** extend and override base templates easily
**So that** I can create custom themes without modifying core files

**Acceptance:**
- Can create `themes/mytheme/views/layouts/main.blade.php`
- Can extend base templates with `@extends('layouts.base')`
- Can override specific sections with `@section('content')`
- Changes don't require core code modifications

**Current Pain:** Must edit PHP files mixing HTML/logic, hardcoded table layouts, global variables everywhere

---

### P2: Developer Adding New Module Views
**As a** module developer
**I want to** create clean, simple view files for my module
**So that** I can focus on logic, not HTML string concatenation

**Acceptance:**
- Create view file: `modules/mymodule/views/profile.blade.php`
- Render with: `return view('mymodule::profile', ['user' => $user])`
- Use template directives: `@if`, `@foreach`, `@include`
- Automatic XSS protection with `{{ $var }}` escaping

**Current Pain:** Must use `$this->addBody("<h1>$title</h1>")`, prone to XSS, mixing concerns

---

### P3: Site Administrator Customizing Appearance
**As a** site admin
**I want to** customize page layouts without PHP knowledge
**So that** I can adjust site appearance through template files

**Acceptance:**
- Edit template files with HTML/CSS only
- Use simple directives (`@if`, `@foreach`)
- No PHP required for basic customization
- Preview changes instantly

**Current Pain:** Must understand PHP, globals, XocpHTML methods, table-based layouts

---

## 📋 Functional Requirements

### FR-001: Template File System
**Must:**
- Support `.blade.php` template files (Laravel-style)
- Or `.php` view files (CodeIgniter-style)
- Organize templates in `themes/{theme}/views/` directory
- Support module-specific views: `modules/{module}/views/`
- Allow view inheritance and includes

**Implementation Notes:**
- Use PSR-4 autoloading
- Template compiler for directives
- File-based caching

---

### FR-002: Template Directives
**Must support:**
- `@extends('layout.main')` - Template inheritance
- `@section('name')` ... `@endsection` - Define sections
- `@yield('name', 'default')` - Render sections
- `@include('partial.header')` - Include sub-templates
- `{{ $variable }}` - Echo with auto-escaping
- `{!! $html !!}` - Raw HTML output
- `@if`, `@elseif`, `@else`, `@endif` - Conditionals
- `@foreach`, `@endforeach` - Loops
- `@php` ... `@endphp` - Raw PHP blocks

**Security:**
- Auto-escape output by default (`{{ }}`)
- Provide raw output when explicitly needed (`{!! !!}`)

---

### FR-003: View Rendering API
**Must provide:**
```php
// Render a view
return view('profile.show', ['user' => $user]);

// Render with shared data
view()->share('sitename', $config['sitename']);

// Check if view exists
if (view()->exists('theme.custom')) { ... }

// Render view to string
$html = view('emails.welcome')->render();
```

**Backward Compatibility:**
- Keep XocpHTML for gradual migration
- Provide adapter: `XocpHTML::fromView($viewName)`

---

### FR-004: Layout System
**Must support:**
- Master layouts (header, footer, sidebar)
- Nested layouts (base → admin → dashboard)
- Dynamic sections (content, scripts, styles)
- Conditional rendering based on user/permissions

**Example structure:**
```
themes/plain/views/
├── layouts/
│   ├── base.blade.php       # Master layout
│   ├── admin.blade.php      # Extends base
│   └── guest.blade.php      # Extends base
├── partials/
│   ├── header.blade.php
│   ├── footer.blade.php
│   └── sidebar.blade.php
└── pages/
    ├── home.blade.php
    └── profile.blade.php
```

---

### FR-005: Theme Integration
**Must:**
- Support theme selection from config
- Auto-detect theme path: `themes/{theme}/views/`
- Fall back to default theme if custom not found
- Allow per-module theme overrides

**Example:**
```php
// In config.php
$xocpConfig['theme'] = 'bootstrap5';

// Renders: themes/bootstrap5/views/home.blade.php
return view('home');
```

---

### FR-006: Caching & Performance
**Should:**
- Compile templates to PHP once
- Cache compiled views in `cache/views/`
- Auto-recompile when template changes
- Support cache clearing: `view()->clearCache()`

**Performance target:**
- Template compilation: <10ms
- Rendered output same speed as current XocpHTML (or faster)

---

### FR-007: Error Handling
**Must:**
- Show clear error messages for missing views
- Display line numbers for template syntax errors
- Provide debug mode with stack traces
- Log template errors

---

## ✅ Success Criteria

### Measurable Outcomes

1. **Developer Experience**
   - Reduce template code by 70% (measured by LOC comparison)
   - Create new view in <5 minutes vs 30 minutes currently
   - 90% of developers prefer new system (survey)

2. **Performance**
   - Page load time ≤ current XocpHTML performance
   - Template compilation <10ms
   - Cache hit ratio >95%

3. **Security**
   - 100% auto-escaping of variables by default
   - Zero XSS vulnerabilities from template rendering
   - Pass OWASP security scan

4. **Backward Compatibility**
   - Existing modules work without modification
   - Gradual migration path (both systems run simultaneously)
   - Clear migration guide provided

5. **Adoption**
   - 3 example templates provided (base, admin, guest)
   - Migration guide with before/after examples
   - All core modules migrated within 2 sprints

---

## 🚫 Edge Cases & Error Scenarios

### EC-001: Missing View File
**Scenario:** Developer references non-existent view
**Expected:** Clear exception with file path and suggestions
**Handling:**
```php
ViewNotFoundException: View [profile.edit] not found.
Searched in:
  - themes/plain/views/profile/edit.blade.php
  - modules/user/views/profile/edit.blade.php
```

### EC-002: Syntax Error in Template
**Scenario:** Malformed Blade directive
**Expected:** Show line number and syntax issue
**Handling:**
```
SyntaxError in themes/plain/views/home.blade.php:15
Unexpected @endfor, expecting @endforeach
```

### EC-003: Circular Template Inheritance
**Scenario:** Layout A extends B, B extends A
**Expected:** Detect and throw exception
**Handling:**
```
CircularInheritanceException: Circular inheritance detected
  layouts.base → layouts.admin → layouts.base
```

### EC-004: Permission-Based View Selection
**Scenario:** Admin vs guest views
**Expected:** Auto-select based on user role
**Handling:**
```php
// Automatically selects admin or guest layout
return view('dashboard')->forUser($user);
```

### EC-005: Theme Not Found
**Scenario:** Configured theme doesn't exist
**Expected:** Fall back to default theme + warning
**Handling:**
```
Warning: Theme 'mytheme' not found, falling back to 'plain'
```

---

## 🔧 Technical Constraints

### Must Use:
- PHP 8.1+ features (enums, readonly, match)
- PSR-4 autoloading
- Composer for dependencies

### Should Avoid:
- Heavy dependencies (prefer lightweight solution)
- Breaking existing XocpHTML until migration complete
- Performance regression

### Consider Using:
- **Option A:** Custom lightweight template engine (800 LOC)
- **Option B:** Laravel Blade standalone (`illuminate/view`)
- **Option C:** CodeIgniter view system

**Recommendation:** Option A (custom) for minimal dependencies and XOCP-specific optimizations

---

## 🎨 Design Principles

1. **Convention over Configuration** - Views in `views/`, layouts in `layouts/`
2. **Progressive Enhancement** - Works without directives, enhanced with them
3. **Security by Default** - Auto-escape unless explicitly opted out
4. **Developer Happiness** - Clear errors, intuitive API, minimal boilerplate
5. **Backward Compatible** - Run alongside XocpHTML during migration

---

## 📊 Comparison: Current vs Proposed

### Current XocpHTML (2002):
```php
// modules/profile/profile.php
class ProfileBlock extends XocpBlock {
    function show() {
        global $xocpDB, $xocp_user;

        $uid = intval($_GET['uid']);
        $sql = "SELECT * FROM users WHERE uid = " . $uid;
        $result = $xocpDB->query($sql);
        $user = $xocpDB->fetchArray($result);

        $html = "<h1>" . $user['name'] . "</h1>";
        $html .= "<p>Email: " . $user['email'] . "</p>";
        $html .= "<table>";
        $html .= "<tr><td>Username:</td><td>" . $user['uname'] . "</td></tr>";
        $html .= "</table>";

        return $html;
    }
}
```

**Problems:**
- HTML concatenation hell
- XSS vulnerabilities (no escaping)
- Mixed concerns (SQL + HTML)
- Global variables
- Hard to maintain

---

### Proposed Template System:
```php
// src/Controllers/ProfileController.php
class ProfileController extends BaseController {
    public function show(int $id) {
        $user = User::find($id);
        return view('profile.show', ['user' => $user]);
    }
}
```

```blade
{{-- themes/plain/views/profile/show.blade.php --}}
@extends('layouts.main')

@section('title', $user->name)

@section('content')
    <h1>{{ $user->name }}</h1>
    <p>Email: {{ $user->email }}</p>

    <table class="user-info">
        <tr>
            <td>Username:</td>
            <td>{{ $user->uname }}</td>
        </tr>
        <tr>
            <td>Member Since:</td>
            <td>{{ $user->created_at->format('M d, Y') }}</td>
        </tr>
    </table>
@endsection
```

**Benefits:**
- Clean separation (controller → view)
- Auto-escaped (XSS-safe)
- Readable HTML
- Inheritance (@extends)
- Maintainable

---

## 📝 Open Questions

1. **Template Engine Choice:**
   - ❓ Use Laravel Blade standalone or custom implementation?
   - **Decision needed:** Week 1

2. **Backward Compatibility:**
   - ❓ How long to maintain XocpHTML alongside new system?
   - **Proposed:** 2 major versions (6 months)

3. **Performance:**
   - ❓ Compile on every request or only when changed?
   - **Proposed:** Compile once, cache, recompile on file change

4. **Migration Strategy:**
   - ❓ Big bang or gradual module-by-module?
   - **Proposed:** Gradual with hybrid support

---

## 🎯 Next Steps

1. **Prototype** - Build minimal working template engine (1 week)
2. **Benchmark** - Compare performance vs XocpHTML
3. **Migrate** - Convert 1 simple module (calendar)
4. **Refine** - Gather feedback, adjust API
5. **Document** - Create migration guide
6. **Rollout** - Migrate all core modules

---

**Last Updated:** 2025-01-08
**Owner:** Claude Code + Team
**Status:** Awaiting approval to proceed with prototype
