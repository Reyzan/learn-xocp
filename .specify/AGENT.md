# Claude Code Agent Configuration

**Project:** XOCP Template Engine Modernization
**Agent:** Claude Code
**Context:** Legacy PHP portal modernization
**Focus:** Template system refactoring

---

## 🎯 Project Context

You are working on modernizing the XOCP (X Open Community Portal) template engine. This is a legacy PHP application from 2002 that needs to be brought into the modern era while maintaining backward compatibility.

### What You're Building
Replace the outdated `XocpHTML` class with a modern template engine similar to Laravel Blade or CodeIgniter views.

### Why It Matters
- Current system: String concatenation hell, XSS vulnerabilities, unmaintainable
- New system: Clean templates, auto-escaping, inheritance, 70% code reduction
- Impact: Easier theme development, faster feature delivery, better security

---

## 📁 Key Files & Locations

### Current Code (Legacy)
```
class/xocphtml.php          # The monster we're replacing
class/xocptheme.php         # Theme system integration
themes/plain/theme.php      # Example theme
```

### New Code (To Be Created)
```
src/View/
├── ViewEngine.php          # Main template engine
├── Compiler.php            # Blade directive compiler
├── FileViewFinder.php      # Template file finder
├── View.php                # View renderer
├── Concerns/               # Compiler traits
│   ├── CompilesConditionals.php
│   ├── CompilesLoops.php
│   └── CompilesLayouts.php
└── Exceptions/
    └── ViewNotFoundException.php

themes/plain/views/         # New template location
└── layouts/
    └── main.blade.php      # Base layout

tests/Unit/View/            # Test suite
```

### Spec Files (Reference)
```
.specify/
├── SPEC.md                 # Feature specification
├── PLAN.md                 # Implementation plan
├── TASKS.md                # Task breakdown
└── README.md               # Overview
```

---

## 💻 Coding Standards

### PHP Version
- **Minimum:** PHP 8.1
- **Target:** PHP 8.3
- Use modern features: enums, readonly, match, constructor property promotion

### Style Guide
- **PSR-12** coding standard
- **PSR-4** autoloading
- Type hints on everything
- Return type declarations
- Readonly classes where appropriate

### Example Code Style
```php
// ✅ Good
readonly class ViewEngine {
    public function __construct(
        private FileViewFinder $finder,
        private Compiler $compiler,
        private string $cachePath
    ) {}

    public function make(string $view, array $data = []): View {
        $path = $this->finder->find($view);
        return new View($path, $data);
    }
}

// ❌ Bad (old style)
class ViewEngine {
    var $finder;
    var $compiler;

    function __construct($finder, $compiler) {
        $this->finder = $finder;
        $this->compiler = $compiler;
    }

    function make($view, $data) {
        return new View($this->finder->find($view), $data);
    }
}
```

---

## 🧪 Testing Requirements

### Test Coverage
- **Minimum:** 90% code coverage
- **Focus:** Edge cases, security, performance

### Test Structure
```php
// tests/Unit/View/ViewEngineTest.php
namespace Tests\Unit\View;

use PHPUnit\Framework\TestCase;
use Xocp\View\ViewEngine;

class ViewEngineTest extends TestCase {
    private ViewEngine $engine;

    protected function setUp(): void {
        $this->engine = new ViewEngine(/* ... */);
    }

    public function testBasicViewRendering(): void {
        $view = $this->engine->make('test', ['name' => 'John']);
        $this->assertStringContainsString('Hello John', $view->render());
    }

    public function testAutoEscaping(): void {
        $view = $this->engine->make('test', [
            'html' => '<script>alert(1)</script>'
        ]);
        $output = $view->render();
        $this->assertStringNotContainsString('<script>', $output);
        $this->assertStringContainsString('&lt;script&gt;', $output);
    }
}
```

### Security Tests
**Critical:** Every feature must have XSS protection tests

```php
public function testXssProtection(): void {
    $malicious = '<script>alert("XSS")</script>';
    $view = view('test', ['input' => $malicious]);
    $output = $view->render();

    // Should be escaped
    $this->assertStringNotContainsString('<script>', $output);
    $this->assertStringContainsString('&lt;script&gt;', $output);
}
```

---

## 🔧 Common Commands

### Running Tests
```bash
# All tests
vendor/bin/phpunit

# Specific test file
vendor/bin/phpunit tests/Unit/View/ViewEngineTest.php

# With coverage
vendor/bin/phpunit --coverage-html coverage/
```

### Code Quality
```bash
# PHP CodeSniffer
vendor/bin/phpcs src/ --standard=PSR12

# PHPStan
vendor/bin/phpstan analyse src/ --level=8

# Fix code style
vendor/bin/phpcbf src/
```

### Benchmarking
```bash
# Performance tests
vendor/bin/phpunit tests/Performance/
```

---

## 🎨 Template Syntax

### Current (XocpHTML - Legacy)
```php
// Ugly string concatenation
$html = "<div class='profile'>";
$html .= "<h1>" . $user['name'] . "</h1>";  // XSS risk!
$html .= "<p>Email: " . $user['email'] . "</p>";
return $html;
```

### New (Blade-like)
```blade
{{-- themes/plain/views/profile/show.blade.php --}}
@extends('layouts.main')

@section('content')
    <div class="profile">
        <h1>{{ $user->name }}</h1>  {{-- Auto-escaped --}}
        <p>Email: {{ $user->email }}</p>
    </div>
@endsection
```

### Supported Directives (Week 2+)
```blade
{{-- Echo with escaping --}}
{{ $variable }}

{{-- Raw HTML (use carefully) --}}
{!! $html !!}

{{-- Conditionals --}}
@if($condition)
    Content
@elseif($other)
    Other
@else
    Default
@endif

{{-- Loops --}}
@foreach($items as $item)
    <li>{{ $item->name }}</li>
@endforeach

{{-- Template inheritance --}}
@extends('layouts.base')
@section('content')
    Content here
@endsection
@yield('content', 'default')

{{-- Includes --}}
@include('partials.header')
@include('partials.menu', ['active' => 'home'])

{{-- Security --}}
@csrf  {{-- CSRF token field --}}
```

---

## 📋 Task Workflow

### When Starting a Task

1. **Read the spec:**
   ```bash
   # Check TASKS.md for task details
   cat .specify/TASKS.md | grep "TASK-XXX" -A 20
   ```

2. **Update status:**
   - Change task from 📋 Todo to 🏃 In Progress
   - Update in TASKS.md

3. **Create branch:**
   ```bash
   git checkout -b feature/template-engine-task-XXX
   ```

4. **Write tests first (TDD):**
   ```bash
   # Create test file first
   touch tests/Unit/View/FeatureTest.php
   # Write failing tests
   # Then implement feature
   ```

### When Completing a Task

1. **Run all tests:**
   ```bash
   vendor/bin/phpunit
   vendor/bin/phpcs
   vendor/bin/phpstan analyse
   ```

2. **Update documentation:**
   - Add PHPDoc comments
   - Update README if needed
   - Add examples

3. **Update task status:**
   - Change to ✅ Done in TASKS.md
   - Note any issues or deviations

4. **Create commit:**
   ```bash
   git add .
   git commit -m "feat(view): implement TASK-XXX feature name

   - Detailed change 1
   - Detailed change 2

   Closes TASK-XXX"
   ```

5. **Push and create PR:**
   ```bash
   git push -u origin feature/template-engine-task-XXX
   # Use PR template from .github/
   ```

---

## 🚨 Important Constraints

### Backward Compatibility
**DO NOT break existing XocpHTML usage!**

- New template engine runs alongside old one
- Gradual migration over 6 months
- Provide adapter: `XocpHTML::fromView()`

### Security First
**Every output must be escaped by default**

- Use `{{ }}` for auto-escaping
- Only allow `{!! !!}` when explicitly needed
- Write security tests for all features

### Performance
**Must be as fast as or faster than XocpHTML**

- Compile templates once
- Cache aggressively
- Benchmark every major feature

### Code Quality
**No shortcuts, do it right**

- 90%+ test coverage
- Type hints everywhere
- PSR-12 compliant
- No deprecated functions

---

## 💡 Development Tips

### When Writing Compiler Code

```php
// Pattern: Compile X to Y
protected function compileDirective(string $value): string {
    // Use regex to find @directive(...)
    return preg_replace(
        '/@directive\s*\((.+?)\)/',
        '<?php directive_php($1); ?>',
        $value
    );
}

// Always handle edge cases
protected function compileIf(string $value): string {
    // Match @if(...) but not @endif
    $pattern = '/@if\s*\((.+?)\)/';

    return preg_replace($pattern, function($matches) {
        // Validate expression
        if (empty($matches[1])) {
            throw new CompileException("Empty @if condition");
        }

        return "<?php if({$matches[1]}): ?>";
    }, $value);
}
```

### When Writing Tests

```php
// Use data providers for multiple scenarios
/**
 * @dataProvider escaping Provider
 */
public function testEscaping(string $input, string $expected): void {
    $view = view('test', ['var' => $input]);
    $this->assertStringContainsString($expected, $view->render());
}

public static function escapingProvider(): array {
    return [
        'script tag' => ['<script>', '&lt;script&gt;'],
        'quote' => ['"test"', '&quot;test&quot;'],
        'ampersand' => ['a & b', 'a &amp; b'],
    ];
}
```

### When Stuck

1. **Check the spec:** Review SPEC.md and PLAN.md
2. **Look at examples:** See Laravel Blade source code
3. **Ask for help:** Update task status to ❌ Blocked
4. **Simplify:** Break task into smaller subtasks

---

## 📊 Progress Tracking

### Update Daily
- Task status in TASKS.md
- Time tracking (estimate vs actual)
- Blockers or issues

### Weekly Review
- Complete tasks: ✅
- Blocked tasks: ❌
- Upcoming tasks: 📋

### Definition of Done
A task is done when:
- ✅ Code written and reviewed
- ✅ Tests passing (90%+ coverage)
- ✅ Documentation updated
- ✅ Performance benchmarks met
- ✅ Security review passed
- ✅ PR merged

---

## 🎯 Current Sprint (Week 1)

**Focus:** Foundation & Prototype

**Priority Tasks:**
- TASK-001: Architecture design
- TASK-002: ViewEngine core
- TASK-003: FileViewFinder
- TASK-004: Basic compiler
- TASK-005: View class

**Goal:** Working prototype by end of week

---

## 🔗 Reference Links

**Inspiration:**
- [Laravel Blade Docs](https://laravel.com/docs/blade)
- [Laravel Blade Source](https://github.com/laravel/framework/tree/master/src/Illuminate/View)
- [CodeIgniter Views](https://codeigniter.com/user_guide/outgoing/views.html)

**PHP Resources:**
- [PHP 8.1 Features](https://www.php.net/releases/8.1/en.php)
- [PSR-12 Standard](https://www.php-fig.org/psr/psr-12/)
- [PHPUnit Docs](https://phpunit.de/documentation.html)

**Project Docs:**
- `MODERNIZATION_GUIDE.md` (repo root)
- `GITHUB_SETUP.md` (repo root)
- `.specify/SPEC.md` (this folder)

---

## 🤖 About Claude Code

**Your Role:**
- Write high-quality, tested code
- Follow spec-driven development
- Maintain backward compatibility
- Prioritize security
- Document everything

**My Strengths:**
- Reading and understanding legacy code
- Designing modern architectures
- Writing comprehensive tests
- Creating clear documentation
- Refactoring safely

**Work Together:**
- I write code, you review
- You provide context, I implement
- We iterate quickly
- Quality over speed

---

**Last Updated:** 2025-01-08
**Status:** Active Development
**Next Task:** TASK-001 (Architecture Design)

---

*Let's build something great together! 🚀*
