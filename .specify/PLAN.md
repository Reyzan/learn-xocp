# Template Engine Modernization - Implementation Plan

**Project:** XOCP Template Engine Modernization
**Duration:** 4 weeks
**Approach:** Incremental, Test-Driven
**Status:** Planning Phase

---

## 🎯 Project Goals

1. Replace XocpHTML with modern template engine
2. Support Laravel Blade-like syntax
3. Maintain backward compatibility
4. Improve developer experience
5. Enhance security (auto-escaping)

---

## 📅 Timeline & Phases

### **Week 1: Foundation & Prototype**
**Goal:** Working prototype with core features

#### Days 1-2: Architecture Design
- [ ] Design template engine architecture
- [ ] Choose approach (custom vs Blade standalone)
- [ ] Define directory structure
- [ ] Create class diagrams

#### Days 3-5: Core Implementation
- [ ] Build `ViewEngine` class
- [ ] Implement file loading
- [ ] Add basic variable substitution `{{ $var }}`
- [ ] Create simple template compiler

#### Days 6-7: Testing
- [ ] Unit tests for ViewEngine
- [ ] Benchmark vs XocpHTML
- [ ] Performance optimization

**Deliverable:** Working prototype that can render basic templates

---

### **Week 2: Directives & Features**
**Goal:** Full directive support

#### Days 8-10: Control Structures
- [ ] Implement `@if/@else/@endif`
- [ ] Implement `@foreach/@endforeach`
- [ ] Implement `@while/@endwhile`
- [ ] Add `@php/@endphp` raw blocks

#### Days 11-12: Template Inheritance
- [ ] Implement `@extends` directive
- [ ] Implement `@section/@endsection`
- [ ] Implement `@yield` directive
- [ ] Handle nested layouts

#### Days 13-14: Includes & Partials
- [ ] Implement `@include` directive
- [ ] Support passing data to includes
- [ ] Add `@includeIf`, `@includeWhen`
- [ ] Test complex template compositions

**Deliverable:** Full-featured template engine with all directives

---

### **Week 3: Integration & Security**
**Goal:** XOCP integration + security hardening

#### Days 15-16: XOCP Integration
- [ ] Create `src/View/` namespace
- [ ] Integrate with theme system
- [ ] Support module-specific views
- [ ] Theme fallback mechanism

#### Days 17-18: Security Features
- [ ] Auto-escaping implementation
- [ ] XSS protection tests
- [ ] CSRF token directive `@csrf`
- [ ] Security audit

#### Days 19-20: Caching System
- [ ] Template compilation caching
- [ ] Cache invalidation
- [ ] Performance benchmarks
- [ ] Cache management commands

#### Day 21: Error Handling
- [ ] Custom exceptions (ViewNotFoundException, etc.)
- [ ] Error messages with context
- [ ] Debug mode with stack traces
- [ ] Production-safe error pages

**Deliverable:** Production-ready template engine

---

### **Week 4: Migration & Documentation**
**Goal:** Migrate modules + complete documentation

#### Days 22-23: Migration Tools
- [ ] Create migration helper scripts
- [ ] XocpHTML → View adapter
- [ ] Backward compatibility layer
- [ ] Migration guide

#### Days 24-26: Module Migration
- [ ] Migrate calendar module (pilot)
- [ ] Migrate project module
- [ ] Migrate system module
- [ ] Update all core modules

#### Days 27-28: Documentation & Examples
- [ ] API documentation
- [ ] Template authoring guide
- [ ] Theme developer guide
- [ ] Example templates (3 themes)

**Deliverable:** Migrated modules + comprehensive docs

---

## 🏗️ Architecture

### Directory Structure
```
src/
├── View/
│   ├── ViewEngine.php          # Main template engine
│   ├── Compiler.php            # Blade directive compiler
│   ├── Factory.php             # View factory
│   ├── FileViewFinder.php      # Template file finder
│   ├── Concerns/
│   │   ├── CompilesConditionals.php
│   │   ├── CompilesLoops.php
│   │   ├── CompilesLayouts.php
│   │   └── CompilesEchos.php
│   └── Exceptions/
│       ├── ViewNotFoundException.php
│       └── CompileException.php
│
themes/
├── plain/
│   └── views/
│       ├── layouts/
│       │   └── main.blade.php
│       ├── partials/
│       │   ├── header.blade.php
│       │   └── footer.blade.php
│       └── pages/
│           └── home.blade.php
│
modules/
└── {module}/
    └── views/
        └── {view}.blade.php

cache/
└── views/                      # Compiled templates
    └── {hash}.php
```

---

## 🔨 Technical Implementation

### Phase 1: Core View Engine

```php
// src/View/ViewEngine.php
namespace Xocp\View;

class ViewEngine {
    private FileViewFinder $finder;
    private Compiler $compiler;
    private string $cachePath;

    public function make(string $view, array $data = []): View {
        $path = $this->finder->find($view);
        $compiled = $this->compiler->compile($path);

        return new View($compiled, $data);
    }

    public function exists(string $view): bool {
        return $this->finder->exists($view);
    }
}
```

### Phase 2: Template Compiler

```php
// src/View/Compiler.php
namespace Xocp\View;

class Compiler {
    use CompilesConditionals;
    use CompilesLoops;
    use CompilesLayouts;
    use CompilesEchos;

    public function compile(string $path): string {
        $contents = file_get_contents($path);

        // Compile directives
        $compiled = $this->compileExtends($contents);
        $compiled = $this->compileSections($compiled);
        $compiled = $this->compileEchos($compiled);
        $compiled = $this->compileConditionals($compiled);
        $compiled = $this->compileLoops($compiled);

        return $compiled;
    }

    protected function compileEchos(string $value): string {
        // {{ $var }} → <?= e($var) ?>
        return preg_replace(
            '/\{\{\s*(.+?)\s*\}\}/',
            '<?= e($1) ?>',
            $value
        );
    }
}
```

### Phase 3: View Factory (Helper Functions)

```php
// src/View/helpers.php

if (!function_exists('view')) {
    function view(string $view = null, array $data = []) {
        $factory = app('view');

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data);
    }
}

if (!function_exists('e')) {
    function e($value): string {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}
```

---

## 🧪 Testing Strategy

### Unit Tests
```php
// tests/Unit/ViewEngineTest.php
class ViewEngineTest extends TestCase {
    public function testBasicViewRendering() {
        $view = view('test.basic', ['name' => 'John']);
        $this->assertEquals('<p>Hello John</p>', $view->render());
    }

    public function testAutoEscaping() {
        $view = view('test.escape', ['html' => '<script>alert(1)</script>']);
        $this->assertStringNotContainsString('<script>', $view->render());
    }

    public function testTemplateInheritance() {
        $view = view('test.child');
        $this->assertStringContainsString('<!DOCTYPE html>', $view->render());
        $this->assertStringContainsString('Child Content', $view->render());
    }
}
```

### Integration Tests
```php
// tests/Integration/ThemeRenderingTest.php
class ThemeRenderingTest extends TestCase {
    public function testThemeSelection() {
        config(['theme' => 'bootstrap5']);
        $view = view('home');
        $this->assertStringContainsString('bootstrap', $view->render());
    }
}
```

### Performance Tests
```php
// tests/Performance/BenchmarkTest.php
class BenchmarkTest extends TestCase {
    public function testRenderingPerformance() {
        $start = microtime(true);
        for ($i = 0; $i < 1000; $i++) {
            view('benchmark.test')->render();
        }
        $time = microtime(true) - $start;

        $this->assertLessThan(1.0, $time); // 1000 renders in <1 second
    }
}
```

---

## 🔄 Migration Strategy

### Gradual Migration Approach

#### Step 1: Hybrid Support
```php
// modules/calendar/calendar.php
class CalendarBlock extends XocpBlock {
    function show() {
        // Old way still works
        if (config('use_legacy_templates')) {
            return $this->showLegacy();
        }

        // New way
        return view('calendar::index', [
            'events' => $this->getEvents()
        ])->render();
    }
}
```

#### Step 2: Module-by-Module
1. **Week 1:** Calendar module (simple, low risk)
2. **Week 2:** Project module (medium complexity)
3. **Week 3:** System module (high complexity)
4. **Week 4:** All remaining modules

#### Step 3: Deprecation
- Mark XocpHTML as `@deprecated` in docs
- Add migration warnings in logs
- Remove in version 2.0 (6 months later)

---

## 📊 Success Metrics

### Before (Current XocpHTML):
```php
// 25 lines of string concatenation hell
$html = "<div class='profile'>";
$html .= "<h1>" . $user['name'] . "</h1>";  // XSS risk!
$html .= "<p>Email: " . $user['email'] . "</p>";
// ... 20 more lines
return $html;
```

### After (New Template Engine):
```blade
{{-- 8 lines of clean HTML --}}
<div class="profile">
    <h1>{{ $user->name }}</h1>
    <p>Email: {{ $user->email }}</p>
    @include('profile.details')
</div>
```

**Metrics:**
- ✅ 68% code reduction (25 → 8 lines)
- ✅ 100% XSS protection
- ✅ 5x easier to read/maintain

---

## 🚧 Risks & Mitigation

### Risk 1: Performance Degradation
**Mitigation:**
- Aggressive template caching
- Benchmark against XocpHTML
- JIT compilation for PHP 8+

### Risk 2: Breaking Changes
**Mitigation:**
- Maintain XocpHTML compatibility
- Gradual migration path
- Version modules independently

### Risk 3: Learning Curve
**Mitigation:**
- Comprehensive documentation
- Video tutorials
- Example templates
- Migration scripts

### Risk 4: Template Syntax Errors
**Mitigation:**
- Clear error messages
- Syntax validation tool
- Linting support (VSCode extension)

---

## 🎓 Team Training

### Week 1: Introduction
- Present new template system
- Live coding demo
- Q&A session

### Week 2: Hands-On Workshop
- Convert sample module together
- Best practices
- Common pitfalls

### Week 3: Office Hours
- Daily support sessions
- Review pull requests
- Answer questions

---

## 📦 Deliverables

### Code
- [ ] `src/View/` namespace (full implementation)
- [ ] Test suite (90%+ coverage)
- [ ] 3 example themes
- [ ] Migration scripts

### Documentation
- [ ] API reference
- [ ] Template authoring guide
- [ ] Theme developer guide
- [ ] Migration guide
- [ ] Video tutorials (3x 10min)

### Examples
- [ ] Basic template
- [ ] Template with inheritance
- [ ] Complex multi-layout example
- [ ] Before/after comparisons

---

## 🔄 Review & Iteration

### Weekly Reviews
- **Monday:** Sprint planning
- **Wednesday:** Mid-week sync
- **Friday:** Demo + retrospective

### Feedback Loops
- Collect developer feedback daily
- Adjust approach based on pain points
- Iterate on API design

---

## 🎯 Definition of Done

A task is considered "done" when:
- ✅ Code written and reviewed
- ✅ Unit tests passing (90%+ coverage)
- ✅ Integration tests passing
- ✅ Documentation updated
- ✅ Performance benchmarks met
- ✅ Security review completed
- ✅ Demo to stakeholders

---

**Status:** Ready to begin Week 1
**Next Action:** Review this plan and approve to proceed
**Questions?** Post in #template-modernization channel
