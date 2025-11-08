# Template Engine Modernization - Task Breakdown

**Project:** XOCP Template Modernization
**Sprint:** 4 weeks
**Task Tracking:** GitHub Issues + This File

---

## 📋 Task Categories

- 🏗️ **Architecture** - Design & structure
- 💻 **Implementation** - Coding tasks
- 🧪 **Testing** - Test creation & execution
- 📚 **Documentation** - Docs & guides
- 🔄 **Migration** - Converting existing code
- ✅ **Review** - Code review & approval

---

## Week 1: Foundation & Prototype

### 🏗️ TASK-001: Design Template Engine Architecture
**Priority:** P0 (Critical)
**Estimate:** 4 hours
**Owner:** Claude Code
**Status:** 📋 Todo

**Description:**
Design the overall architecture for the new template engine.

**Subtasks:**
- [ ] Research Laravel Blade architecture
- [ ] Research CodeIgniter view system
- [ ] Design class hierarchy (`ViewEngine`, `Compiler`, `Factory`)
- [ ] Create UML diagrams
- [ ] Document design decisions

**Acceptance Criteria:**
- Architecture diagram created
- Class responsibilities documented
- Design approved by team

**Files:**
- `.specify/ARCHITECTURE.md`
- `.specify/diagrams/class-diagram.md`

---

### 💻 TASK-002: Create ViewEngine Core Class
**Priority:** P0 (Critical)
**Estimate:** 6 hours
**Depends on:** TASK-001
**Status:** 📋 Todo

**Description:**
Implement the core `ViewEngine` class that handles template loading and rendering.

**Implementation:**
```php
// src/View/ViewEngine.php
namespace Xocp\View;

readonly class ViewEngine {
    public function __construct(
        private FileViewFinder $finder,
        private Compiler $compiler,
        private string $cachePath
    ) {}

    public function make(string $view, array $data = []): View {
        $path = $this->finder->find($view);
        $compiled = $this->compiler->compile($path);
        return new View($compiled, $data);
    }

    public function exists(string $view): bool {
        return $this->finder->exists($view);
    }

    public function share(string $key, mixed $value): void {
        // Share data across all views
    }
}
```

**Acceptance Criteria:**
- [ ] Class created with PHP 8.1+ features
- [ ] Constructor property promotion used
- [ ] Readonly class for immutability
- [ ] Type hints on all methods
- [ ] PHPDoc comments added

**Tests:**
- `tests/Unit/View/ViewEngineTest.php`

---

### 💻 TASK-003: Implement FileViewFinder
**Priority:** P0 (Critical)
**Estimate:** 4 hours
**Depends on:** TASK-001
**Status:** 📋 Todo

**Description:**
Create class that locates view files across themes and modules.

**Implementation:**
```php
// src/View/FileViewFinder.php
namespace Xocp\View;

class FileViewFinder {
    private array $paths = [];
    private array $extensions = ['.blade.php', '.php'];

    public function find(string $name): string {
        // Convert dot notation to path
        // 'profile.show' → 'profile/show.blade.php'

        foreach ($this->paths as $path) {
            foreach ($this->extensions as $ext) {
                $file = "$path/$name$ext";
                if (file_exists($file)) {
                    return $file;
                }
            }
        }

        throw new ViewNotFoundException("View [$name] not found");
    }

    public function addPath(string $path): void {
        $this->paths[] = $path;
    }
}
```

**Acceptance Criteria:**
- [ ] Supports dot notation (profile.show)
- [ ] Searches multiple paths (themes, modules)
- [ ] Tries multiple extensions (.blade.php, .php)
- [ ] Throws clear exception when not found

**Tests:**
- `tests/Unit/View/FileViewFinderTest.php`

---

### 💻 TASK-004: Build Basic Template Compiler
**Priority:** P0 (Critical)
**Estimate:** 8 hours
**Depends on:** TASK-001
**Status:** 📋 Todo

**Description:**
Create template compiler that converts Blade syntax to PHP.

**Implementation:**
```php
// src/View/Compiler.php
namespace Xocp\View;

class Compiler {
    private string $cachePath;

    public function compile(string $path): string {
        $hash = md5($path);
        $compiled = "{$this->cachePath}/$hash.php";

        // Recompile if source changed
        if (!file_exists($compiled) || filemtime($path) > filemtime($compiled)) {
            $contents = file_get_contents($path);
            $php = $this->compileString($contents);
            file_put_contents($compiled, $php);
        }

        return $compiled;
    }

    protected function compileString(string $value): string {
        // Week 1: Basic echo statements only
        // {{ $var }} → <?= e($var) ?>
        return preg_replace(
            '/\{\{\s*(.+?)\s*\}\}/',
            '<?= e($1) ?>',
            $value
        );
    }
}
```

**Acceptance Criteria:**
- [ ] Compiles `{{ $var }}` to escaped output
- [ ] Caches compiled templates
- [ ] Recompiles when source changes
- [ ] Returns path to compiled file

**Tests:**
- `tests/Unit/View/CompilerTest.php`

---

### 💻 TASK-005: Create View Class
**Priority:** P0 (Critical)
**Estimate:** 3 hours
**Depends on:** TASK-002
**Status:** 📋 Todo

**Description:**
Implement `View` class that renders compiled templates.

**Implementation:**
```php
// src/View/View.php
namespace Xocp\View;

class View {
    public function __construct(
        private string $path,
        private array $data = []
    ) {}

    public function render(): string {
        extract($this->data);

        ob_start();
        include $this->path;
        return ob_get_clean();
    }

    public function with(string $key, mixed $value): self {
        $this->data[$key] = $value;
        return $this;
    }

    public function __toString(): string {
        return $this->render();
    }
}
```

**Acceptance Criteria:**
- [ ] Renders compiled PHP
- [ ] Passes data to template
- [ ] Supports method chaining
- [ ] Implements __toString for echo

**Tests:**
- `tests/Unit/View/ViewTest.php`

---

### 🧪 TASK-006: Write Unit Tests for Core Classes
**Priority:** P1 (High)
**Estimate:** 6 hours
**Depends on:** TASK-002, TASK-003, TASK-004, TASK-005
**Status:** 📋 Todo

**Description:**
Create comprehensive unit tests for all core classes.

**Test Files:**
```
tests/Unit/View/
├── ViewEngineTest.php
├── FileViewFinderTest.php
├── CompilerTest.php
└── ViewTest.php
```

**Test Cases:**
- Basic view rendering
- Variable substitution
- Auto-escaping
- File not found handling
- Cache invalidation
- Multiple search paths

**Acceptance Criteria:**
- [ ] 90%+ code coverage
- [ ] All edge cases covered
- [ ] Performance tests included

---

### 🧪 TASK-007: Benchmark vs XocpHTML
**Priority:** P1 (High)
**Estimate:** 3 hours
**Depends on:** TASK-006
**Status:** 📋 Todo

**Description:**
Create performance benchmarks comparing new engine to XocpHTML.

**Benchmark Script:**
```php
// tests/Performance/ViewBenchmark.php
$iterations = 10000;

// Old way
$start = microtime(true);
for ($i = 0; $i < $iterations; $i++) {
    $html = new XocpHTML();
    $html->addBody("<h1>Test</h1>");
    $output = $html->out();
}
$oldTime = microtime(true) - $start;

// New way
$start = microtime(true);
for ($i = 0; $i < $iterations; $i++) {
    $output = view('test')->render();
}
$newTime = microtime(true) - $start;

echo "Old: {$oldTime}s\nNew: {$newTime}s\n";
echo "Speedup: " . round($oldTime / $newTime, 2) . "x\n";
```

**Acceptance Criteria:**
- [ ] New engine ≥ same speed as XocpHTML
- [ ] Memory usage documented
- [ ] Cache hit/miss ratios measured

---

## Week 2: Directives & Features

### 💻 TASK-008: Implement Conditional Directives
**Priority:** P0 (Critical)
**Estimate:** 6 hours
**Status:** 📋 Todo

**Description:**
Add support for `@if`, `@elseif`, `@else`, `@endif`.

**Implementation:**
```php
// src/View/Concerns/CompilesConditionals.php
trait CompilesConditionals {
    protected function compileIf(string $value): string {
        // @if($condition) → <?php if($condition): ?>
        return preg_replace(
            '/@if\s*\((.+?)\)/',
            '<?php if($1): ?>',
            $value
        );
    }

    protected function compileElseif(string $value): string {
        return preg_replace(
            '/@elseif\s*\((.+?)\)/',
            '<?php elseif($1): ?>',
            $value
        );
    }

    protected function compileElse(string $value): string {
        return str_replace('@else', '<?php else: ?>', $value);
    }

    protected function compileEndif(string $value): string {
        return str_replace('@endif', '<?php endif; ?>', $value);
    }
}
```

**Acceptance Criteria:**
- [ ] @if/@elseif/@else/@endif work correctly
- [ ] Nested conditionals supported
- [ ] Edge cases handled (missing @endif, etc.)

**Tests:**
- `tests/Unit/View/CompilerConditionalsTest.php`

---

### 💻 TASK-009: Implement Loop Directives
**Priority:** P0 (Critical)
**Estimate:** 6 hours
**Status:** 📋 Todo

**Description:**
Add `@foreach`, `@for`, `@while` directives.

**Implementation:**
```php
// src/View/Concerns/CompilesLoops.php
trait CompilesLoops {
    protected function compileForeach(string $value): string {
        // @foreach($items as $item) → <?php foreach($items as $item): ?>
        return preg_replace(
            '/@foreach\s*\((.+?)\)/',
            '<?php foreach($1): ?>',
            $value
        );
    }

    protected function compileEndforeach(string $value): string {
        return str_replace('@endforeach', '<?php endforeach; ?>', $value);
    }

    protected function compileFor(string $value): string {
        return preg_replace(
            '/@for\s*\((.+?)\)/',
            '<?php for($1): ?>',
            $value
        );
    }

    protected function compileWhile(string $value): string {
        return preg_replace(
            '/@while\s*\((.+?)\)/',
            '<?php while($1): ?>',
            $value
        );
    }
}
```

**Acceptance Criteria:**
- [ ] @foreach, @for, @while work
- [ ] Loop variables accessible in template
- [ ] Nested loops supported

---

### 💻 TASK-010: Implement Template Inheritance (@extends)
**Priority:** P0 (Critical)
**Estimate:** 10 hours
**Status:** 📋 Todo

**Description:**
Add `@extends`, `@section`, `@yield` for layout inheritance.

**Complexity:** High (most complex feature)

**Acceptance Criteria:**
- [ ] Child can extend parent layout
- [ ] Sections override parent sections
- [ ] @yield renders sections
- [ ] Multi-level inheritance works
- [ ] Circular inheritance detected

---

### 💻 TASK-011: Implement Include Directive
**Priority:** P1 (High)
**Estimate:** 4 hours
**Depends on:** TASK-010
**Status:** 📋 Todo

**Description:**
Add `@include` for template partials.

**Implementation:**
```php
protected function compileInclude(string $value): string {
    // @include('partial.header') → <?php echo view('partial.header')->render(); ?>
    return preg_replace(
        '/@include\s*\([\'"](.+?)[\'"]\)/',
        '<?php echo view(\'$1\')->render(); ?>',
        $value
    );
}
```

**Acceptance Criteria:**
- [ ] Can include partials
- [ ] Can pass data to includes
- [ ] Supports @includeIf, @includeWhen

---

## Week 3: Integration & Security

### 💻 TASK-012: Integrate with XOCP Theme System
**Priority:** P0 (Critical)
**Estimate:** 8 hours
**Status:** 📋 Todo

**Description:**
Connect view engine to existing XOCP theme configuration.

**Implementation:**
```php
// bootstrap/view.php
$container->singleton('view', function() use ($xocpConfig) {
    $finder = new FileViewFinder();

    // Add theme paths
    $theme = $xocpConfig['theme'] ?? 'plain';
    $finder->addPath(XOCP_DOC_ROOT . "/themes/$theme/views");
    $finder->addPath(XOCP_DOC_ROOT . "/themes/plain/views"); // Fallback

    // Add module paths
    foreach (glob(XOCP_DOC_ROOT . "/modules/*") as $module) {
        $finder->addPath("$module/views");
    }

    $compiler = new Compiler(XOCP_DOC_ROOT . '/cache/views');
    return new ViewEngine($finder, $compiler, $xocpConfig);
});
```

**Acceptance Criteria:**
- [ ] Respects theme selection from config
- [ ] Falls back to default theme
- [ ] Supports module-specific views

---

### 💻 TASK-013: Implement Auto-Escaping
**Priority:** P0 (Critical - Security)
**Estimate:** 4 hours
**Status:** 📋 Todo

**Description:**
Ensure all `{{ }}` output is auto-escaped to prevent XSS.

**Implementation:**
```php
// src/View/helpers.php
function e(mixed $value, bool $doubleEncode = true): string {
    if ($value instanceof Htmlable) {
        return $value->toHtml();
    }

    return htmlspecialchars(
        $value ?? '',
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8',
        $doubleEncode
    );
}
```

**Security Tests:**
```php
public function testXssProtection() {
    $view = view('test', ['input' => '<script>alert(1)</script>']);
    $output = $view->render();
    $this->assertStringNotContainsString('<script>', $output);
    $this->assertStringContainsString('&lt;script&gt;', $output);
}
```

**Acceptance Criteria:**
- [ ] All {{ }} output escaped
- [ ] {!! !!} allows raw HTML
- [ ] XSS test suite passes

---

### 💻 TASK-014: Add CSRF Token Directive
**Priority:** P1 (High - Security)
**Estimate:** 2 hours
**Depends on:** TASK-013
**Status:** 📋 Todo

**Description:**
Add `@csrf` directive for form protection.

**Implementation:**
```php
protected function compileCsrf(string $value): string {
    return str_replace(
        '@csrf',
        '<?php echo csrfField(); ?>',
        $value
    );
}
```

**Usage:**
```blade
<form method="POST">
    @csrf
    <input name="email">
</form>
```

---

### 💻 TASK-015: Implement Template Caching
**Priority:** P1 (High - Performance)
**Estimate:** 6 hours
**Status:** 📋 Todo

**Description:**
Add intelligent caching with invalidation.

**Features:**
- Cache compiled templates
- Invalidate when source changes
- Manual cache clearing
- Cache statistics

**Acceptance Criteria:**
- [ ] Templates compiled once
- [ ] Auto-recompile on change
- [ ] Cache clear command works
- [ ] Performance improved

---

## Week 4: Migration & Documentation

### 🔄 TASK-016: Create Migration Helper Scripts
**Priority:** P1 (High)
**Estimate:** 8 hours
**Status:** 📋 Todo

**Description:**
Build tools to help migrate from XocpHTML to views.

**Tools:**
1. **Analyzer:** Scan code for XocpHTML usage
2. **Converter:** Auto-convert simple cases
3. **Validator:** Check template syntax

**Acceptance Criteria:**
- [ ] Can identify all XocpHTML usage
- [ ] Converts 80%+ automatically
- [ ] Generates migration report

---

### 🔄 TASK-017: Migrate Calendar Module (Pilot)
**Priority:** P0 (Critical)
**Estimate:** 6 hours
**Depends on:** TASK-016
**Status:** 📋 Todo

**Description:**
Convert calendar module as proof of concept.

**Before:** `modules/calendar/calendar.php` (XocpHTML)
**After:** `modules/calendar/views/*.blade.php`

**Acceptance Criteria:**
- [ ] Full functionality preserved
- [ ] Tests pass
- [ ] Code reduced by 60%+
- [ ] Team approves approach

---

### 📚 TASK-018: Write API Documentation
**Priority:** P1 (High)
**Estimate:** 8 hours
**Status:** 📋 Todo

**Description:**
Create comprehensive API docs.

**Sections:**
1. Quick Start
2. View Rendering
3. Template Syntax
4. Directives Reference
5. Advanced Features

**Format:** Markdown + code examples

---

### 📚 TASK-019: Create Theme Developer Guide
**Priority:** P1 (High)
**Estimate:** 6 hours
**Status:** 📋 Todo

**Description:**
Guide for creating custom themes.

**Topics:**
- Directory structure
- Layout system
- Template inheritance
- Best practices
- Example themes

---

### 📚 TASK-020: Build Example Templates
**Priority:** P2 (Medium)
**Estimate:** 12 hours
**Status:** 📋 Todo

**Description:**
Create 3 example themes showcasing features.

**Themes:**
1. **Plain:** Basic HTML5
2. **Bootstrap5:** Modern responsive
3. **Admin:** Dashboard layout

**Acceptance Criteria:**
- [ ] 3 complete themes
- [ ] Documented and commented
- [ ] Responsive design
- [ ] Accessible (WCAG AA)

---

## 📊 Task Summary

### By Priority
- **P0 (Critical):** 12 tasks
- **P1 (High):** 7 tasks
- **P2 (Medium):** 1 task

### By Category
- 🏗️ Architecture: 1
- 💻 Implementation: 14
- 🧪 Testing: 2
- 📚 Documentation: 3
- 🔄 Migration: 2

### By Week
- **Week 1:** 7 tasks (Foundation)
- **Week 2:** 4 tasks (Features)
- **Week 3:** 4 tasks (Integration)
- **Week 4:** 5 tasks (Migration)

---

## 🎯 Task Status Legend

- 📋 **Todo** - Not started
- 🏃 **In Progress** - Currently working
- 🔍 **In Review** - Code review
- ✅ **Done** - Completed & merged
- ❌ **Blocked** - Waiting on dependency
- ⏸️ **Paused** - Temporarily on hold

---

## 📝 Notes

- Update task status daily
- Link GitHub issues to tasks
- Add time tracking for estimates
- Review blockers in daily standup

---

**Last Updated:** 2025-01-08
**Next Review:** Daily standup
