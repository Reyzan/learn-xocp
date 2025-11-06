# PHP Modernization Guide for XOCP

## Executive Summary

Your XOCP codebase is a **custom PHP framework from 2002-2003** with a modular architecture. It predates modern frameworks and uses deprecated PHP patterns that make it difficult to add features. This guide provides a step-by-step modernization roadmap.

---

## Current State Assessment

### What You Have
- **Custom MVC-like Framework** (not Laravel/CI)
- **Modular Plugin System** (modules can be added/removed)
- **Database Abstraction** (MySQL/PostgreSQL support)
- **Theme System** (basic templating)
- **Form Building Framework**
- **Object-Oriented Base Classes**

### Critical Problems

#### 🔴 **Blocker Issues** (Must Fix for PHP 7+)
1. **Deprecated `mysql_*` functions** → Removed in PHP 7.0
2. **`session_register()`** → Removed in PHP 5.4
3. **Old superglobals** (`$HTTP_GET_VARS`, etc.) → Use `$_GET`, `$_POST`
4. **Global variable pollution** → Every file uses 10+ globals

#### 🟠 **Security Vulnerabilities**
1. **SQL Injection** → Using `addslashes()` instead of prepared statements
2. **XSS Vulnerabilities** → Inconsistent output escaping
3. **Weak Password Hashing** → MD5 instead of `password_hash()`
4. **Session Fixation Risks** → Old session handling

#### 🟡 **Architecture Issues** (Why Adding Features is Hell)
1. **No Autoloading** → Manual `require()` everywhere
2. **No Dependency Injection** → Everything is global
3. **Mixed Concerns** → HTML, business logic, and data access mixed together
4. **No Testing** → Can't verify changes work
5. **No Modern Routing** → URL parameters like `?X_calendar=view`

---

## Modernization Strategy: Two Paths

### Path A: Full Rewrite with Laravel (6-12 months)
**Best for:** Long-term maintainability, modern features, large team

**Pros:**
- Modern architecture (MVC, middleware, ORM)
- Built-in authentication, validation, testing
- Large ecosystem (packages, community)
- Easy to hire developers

**Cons:**
- Complete rewrite required
- Learning curve
- Migration complexity

### Path B: Incremental Modernization (2-4 months)
**Best for:** Keep existing code working while improving it step-by-step

**Pros:**
- No complete rewrite
- Can run old and new code side-by-side
- Lower risk
- Immediate improvements

**Cons:**
- Technical debt remains
- Mixed old/new patterns
- Not as clean as fresh start

---

## RECOMMENDED: Path B - Incremental Modernization

We'll modernize in 5 phases, each adding value without breaking existing features.

---

## Phase 1: Foundation (Week 1-2)

### Goal: Make code runnable on PHP 7.4+ and secure

#### Step 1.1: Install Composer
```bash
cd /home/user/learn-xocp
composer init
```

Create `composer.json`:
```json
{
    "name": "xocp/portal",
    "description": "XOCP Community Portal",
    "require": {
        "php": ">=7.4",
        "vlucas/phpdotenv": "^5.5",
        "monolog/monolog": "^2.0"
    },
    "autoload": {
        "psr-4": {
            "Xocp\\": "src/"
        },
        "files": [
            "include/functions.php"
        ]
    }
}
```

#### Step 1.2: Replace Deprecated MySQL Functions

**Before:**
```php
// class/database/mysql.php
$this->dbh = mysql_connect($host, $user, $pass);
mysql_select_db($dbname, $this->dbh);
$result = mysql_query($sql, $this->dbh);
```

**After:**
```php
// class/database/mysql.php
$this->dbh = new mysqli($host, $user, $pass, $dbname);
if ($this->dbh->connect_error) {
    die("Connection failed: " . $this->dbh->connect_error);
}
$result = $this->dbh->query($sql);
```

**Better (PDO):**
```php
// class/database/pdo.php (new file)
class PDODatabase extends DBConnection {
    private $pdo;

    public function connect($host, $user, $pass, $dbname) {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
```

#### Step 1.3: Fix Session Management

**Before:**
```php
// class/xocpsession.php
session_start();
session_register("xocp_user");
session_register("xocpsid");
```

**After:**
```php
// class/xocpsession.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['xocp_user'] = $xocp_user;
$_SESSION['xocpsid'] = $xocpsid;
```

#### Step 1.4: Replace Superglobals

**Before:**
```php
$HTTP_GET_VARS['module']
$HTTP_POST_VARS['username']
$HTTP_COOKIE_VARS['session']
```

**After:**
```php
$_GET['module']
$_POST['username']
$_COOKIE['session']
```

#### Step 1.5: Add Environment Config

Create `.env`:
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=xocp
DB_USERNAME=your_username
DB_PASSWORD=your_password
DB_PREFIX=

APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost/xocp

SESSION_LIFETIME=7200
```

Update `config.php`:
```php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$xocpConfig['dbhost'] = $_ENV['DB_HOST'];
$xocpConfig['dbuname'] = $_ENV['DB_USERNAME'];
// etc.
```

---

## Phase 2: Security Hardening (Week 3)

### Step 2.1: Implement Prepared Statements

**Before (VULNERABLE):**
```php
// class/xocpuser.php
function load($uid) {
    $sql = "SELECT * FROM users WHERE uid = " . addslashes($uid);
    $result = $this->db->query($sql);
}
```

**After (SECURE):**
```php
function load($uid) {
    $sql = "SELECT * FROM users WHERE uid = ?";
    $result = $this->db->query($sql, [(int)$uid]);
    // PDO prepared statement prevents SQL injection
}
```

### Step 2.2: Fix Password Hashing

**Before:**
```php
// MD5 hashing (INSECURE)
$password_hash = md5($password);
```

**After:**
```php
// Modern password hashing
function hashPassword($password) {
    return password_hash($password, PASSWORD_ARGON2ID);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}
```

**Migration script:**
```sql
-- Add new column for modern hashes
ALTER TABLE users ADD COLUMN password_hash VARCHAR(255) NULL;

-- Old passwords remain in `pass` column until user logs in
-- On login, if password_hash is NULL, verify with MD5, then rehash
```

### Step 2.3: XSS Protection

Create helper function:
```php
// include/security.php
function escape($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function escapeJs($value) {
    return json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP);
}
```

**Before:**
```php
echo $user->getVar('uname');
```

**After:**
```php
echo escape($user->getVar('uname'));
```

### Step 2.4: CSRF Protection

```php
// include/csrf.php
function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . escape(generateCsrfToken()) . '">';
}
```

Add to forms:
```php
$form->addElement(new XocpFormHidden('csrf_token', generateCsrfToken()));
```

---

## Phase 3: Modern Architecture (Week 4-6)

### Step 3.1: Introduce PSR-4 Autoloading

Create new namespace structure:
```
src/
├── Controllers/
│   ├── BaseController.php
│   ├── AuthController.php
│   └── ProjectController.php
├── Models/
│   ├── User.php
│   ├── Group.php
│   └── Project.php
├── Services/
│   ├── AuthService.php
│   └── PermissionService.php
├── Database/
│   ├── Connection.php
│   └── QueryBuilder.php
└── Http/
    ├── Request.php
    ├── Response.php
    └── Router.php
```

Example modern controller:
```php
// src/Controllers/AuthController.php
namespace Xocp\Controllers;

use Xocp\Models\User;
use Xocp\Services\AuthService;

class AuthController extends BaseController {
    private $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($user = $this->authService->attempt($username, $password)) {
                $_SESSION['user_id'] = $user->getId();
                redirect('/dashboard');
            }

            return $this->view('auth/login', [
                'error' => 'Invalid credentials'
            ]);
        }

        return $this->view('auth/login');
    }
}
```

### Step 3.2: Add Simple Router

```php
// src/Http/Router.php
namespace Xocp\Http;

class Router {
    private $routes = [];

    public function get($path, $handler) {
        $this->routes['GET'][$path] = $handler;
    }

    public function post($path, $handler) {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch($method, $uri) {
        $uri = strtok($uri, '?'); // Remove query string

        if (isset($this->routes[$method][$uri])) {
            $handler = $this->routes[$method][$uri];

            if (is_callable($handler)) {
                return $handler();
            }

            // Controller@method format
            if (is_string($handler) && strpos($handler, '@') !== false) {
                [$controller, $method] = explode('@', $handler);
                $controller = "Xocp\\Controllers\\$controller";
                return (new $controller)->$method();
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
```

**Usage (routes.php):**
```php
$router = new \Xocp\Http\Router();

$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->get('/projects', 'ProjectController@index');
$router->get('/projects/{id}', 'ProjectController@show');
```

**Update index.php:**
```php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

$router = require __DIR__ . '/routes.php';
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
```

### Step 3.3: Dependency Injection Container

```php
// src/Container.php
namespace Xocp;

class Container {
    private $bindings = [];
    private $instances = [];

    public function bind($abstract, $concrete) {
        $this->bindings[$abstract] = $concrete;
    }

    public function singleton($abstract, $concrete) {
        $this->bind($abstract, $concrete);
    }

    public function make($abstract) {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $concrete = $this->bindings[$abstract] ?? $abstract;

        if (is_callable($concrete)) {
            $object = $concrete($this);
        } else {
            $object = new $concrete();
        }

        $this->instances[$abstract] = $object;
        return $object;
    }
}
```

**Usage:**
```php
// bootstrap.php
$container = new \Xocp\Container();

$container->singleton('db', function() use ($xocpConfig) {
    return new \Xocp\Database\Connection(
        $xocpConfig['dbhost'],
        $xocpConfig['dbuname'],
        $xocpConfig['dbpass'],
        $xocpConfig['dbname']
    );
});

$container->bind(\Xocp\Services\AuthService::class, function($c) {
    return new \Xocp\Services\AuthService($c->make('db'));
});
```

---

## Phase 4: Modern ORM/Query Builder (Week 7-8)

Instead of raw SQL everywhere, introduce a query builder:

### Option A: Use Eloquent Standalone
```bash
composer require illuminate/database
```

```php
// bootstrap.php
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => $_ENV['DB_HOST'],
    'database' => $_ENV['DB_DATABASE'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
```

**Modern Model:**
```php
// src/Models/User.php
namespace Xocp\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = 'users';
    protected $primaryKey = 'uid';

    protected $fillable = ['uname', 'email', 'name'];
    protected $hidden = ['pass', 'password_hash'];

    public function groups() {
        return $this->belongsToMany(Group::class, 'lnk_users_groups', 'uid', 'gid');
    }

    public function hasPermission($module) {
        return $this->groups()
            ->whereHas('modules', function($q) use ($module) {
                $q->where('module_name', $module);
            })
            ->exists();
    }
}
```

**Usage:**
```php
// Before (old way)
$user = new XocpUser();
$user->load($uid);
$uname = $user->getVar('uname');

// After (modern way)
$user = User::find($uid);
$uname = $user->uname;

// Query examples
$activeUsers = User::where('status', 'active')->get();
$admins = User::whereHas('groups', fn($q) => $q->where('name', 'Admin'))->get();
```

### Option B: Build Simple Query Builder

```php
// src/Database/QueryBuilder.php
namespace Xocp\Database;

class QueryBuilder {
    private $pdo;
    private $table;
    private $wheres = [];
    private $bindings = [];

    public function table($table) {
        $this->table = $table;
        return $this;
    }

    public function where($column, $operator, $value = null) {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $this->wheres[] = "$column $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function get() {
        $sql = "SELECT * FROM {$this->table}";

        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->fetchAll();
    }

    public function first() {
        return $this->get()[0] ?? null;
    }
}
```

---

## Phase 5: Testing & CI/CD (Week 9-10)

### Step 5.1: Add PHPUnit
```bash
composer require --dev phpunit/phpunit
```

**phpunit.xml:**
```xml
<?xml version="1.0"?>
<phpunit bootstrap="vendor/autoload.php">
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

**Example Test:**
```php
// tests/Unit/UserTest.php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Xocp\Models\User;

class UserTest extends TestCase {
    public function testUserCanBeCreated() {
        $user = new User();
        $user->uname = 'testuser';
        $user->email = 'test@example.com';

        $this->assertEquals('testuser', $user->uname);
    }

    public function testPasswordHashingWorks() {
        $password = 'secretpassword';
        $hash = password_hash($password, PASSWORD_ARGON2ID);

        $this->assertTrue(password_verify($password, $hash));
        $this->assertFalse(password_verify('wrongpassword', $hash));
    }
}
```

### Step 5.2: Add Code Quality Tools
```bash
composer require --dev squizlabs/php_codesniffer
composer require --dev phpstan/phpstan
```

**.phpcs.xml:**
```xml
<?xml version="1.0"?>
<ruleset name="XOCP">
    <rule ref="PSR12"/>
    <file>src</file>
    <file>modules</file>
</ruleset>
```

Run checks:
```bash
vendor/bin/phpcs
vendor/bin/phpstan analyse src
```

---

## Migration Strategy: Running Old & New Side-by-Side

### Hybrid Routing Approach

```php
// index.php (updated)
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/include/common.php';

// Check if this is a new route
$uri = strtok($_SERVER['REQUEST_URI'], '?');
$modernRoutes = ['/login', '/logout', '/api/*', '/admin/*'];

$isModernRoute = false;
foreach ($modernRoutes as $route) {
    if (fnmatch($route, $uri)) {
        $isModernRoute = true;
        break;
    }
}

if ($isModernRoute) {
    // Use new router
    $router = require __DIR__ . '/routes.php';
    $router->dispatch($_SERVER['REQUEST_METHOD'], $uri);
} else {
    // Use legacy module system
    $module = catchVar("module");
    if ($module) {
        include_once "modules/$module/$module.php";
    } else {
        // Default page rendering
        $xocpHTML = new XocpHTML();
        echo $xocpHTML->pageFromFile($xocpConfig['startpage']);
    }
}
```

This way you can:
1. Keep old modules working
2. Build new features with modern code
3. Gradually migrate old modules
4. No "big bang" rewrite risk

---

## Comparison: Before vs After

### Adding a New Feature: "User Profile Page"

#### BEFORE (Current Hell)
```php
// modules/profile/profile.php
require_once "../../config.php";
require_once XOCP_DOC_ROOT . "/include/common.php";

global $xocpDB, $xocp_user, $xocpConfig;

$uid = intval($HTTP_GET_VARS['uid']);
$sql = "SELECT * FROM " . XOCP_PREFIX . "users WHERE uid = " . addslashes($uid);
$result = $xocpDB->query($sql);
$userdata = $xocpDB->fetchArray($result);

$xocpHTML = new XocpHTML();
$xocpHTML->setTitle($userdata['name']);

$theme = new plain(); // Hardcoded theme class

echo $theme->openPage();
echo "<h1>" . $userdata['name'] . "</h1>";
echo "<p>Email: " . $userdata['email'] . "</p>";
echo $theme->closePage();
```

**Problems:**
- Global variables everywhere
- SQL injection risk
- XSS vulnerability
- No reusability
- Hard to test
- Mixed HTML/PHP

#### AFTER (Modern Approach)
```php
// routes.php
$router->get('/profile/{id}', 'ProfileController@show');
```

```php
// src/Controllers/ProfileController.php
namespace Xocp\Controllers;

use Xocp\Models\User;

class ProfileController extends BaseController {
    public function show($id) {
        $user = User::findOrFail($id);

        return $this->view('profile/show', [
            'user' => $user,
            'projects' => $user->projects
        ]);
    }
}
```

```php
// views/profile/show.php
<?php $this->extend('layouts/main') ?>

<h1><?= escape($user->name) ?></h1>
<p>Email: <?= escape($user->email) ?></p>

<h2>Projects</h2>
<ul>
    <?php foreach ($projects as $project): ?>
        <li><?= escape($project->name) ?></li>
    <?php endforeach ?>
</ul>
```

**Benefits:**
- No globals
- SQL injection impossible (ORM)
- XSS protected (escape helper)
- Reusable controller
- Testable
- Clean separation

---

## Quick Wins: Immediate Improvements (This Week)

### 1. Add `.gitignore`
```
/vendor/
.env
/cache/*
!/cache/.gitkeep
```

### 2. Add Error Handling
```php
// config.php
if ($_ENV['APP_DEBUG'] === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/logs/php-errors.log');
}
```

### 3. Add Logging
```php
// bootstrap.php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('xocp');
$logger->pushHandler(new StreamHandler(__DIR__ . '/logs/app.log', Logger::DEBUG));

// Usage
$logger->info('User logged in', ['uid' => $user->getId()]);
$logger->error('Database connection failed', ['error' => $e->getMessage()]);
```

### 4. Database Migration System
```php
// migrations/001_add_password_hash_column.php
return [
    'up' => "ALTER TABLE users ADD COLUMN password_hash VARCHAR(255) NULL",
    'down' => "ALTER TABLE users DROP COLUMN password_hash"
];
```

---

## Learning Resources

### If You Choose Laravel
- **Official Docs:** https://laravel.com/docs
- **Laracasts:** https://laracasts.com (video tutorials)
- **Laravel Bootcamp:** Free interactive tutorial

### If You Choose CodeIgniter 4
- **Docs:** https://codeigniter.com/user_guide
- **Simpler than Laravel, closer to your current code**

### Modern PHP Practices
- **PHP The Right Way:** https://phptherightway.com
- **PSR Standards:** https://www.php-fig.org/psr
- **Composer:** https://getcomposer.org/doc

---

## Decision Matrix: What Should You Do?

| Scenario | Recommendation |
|----------|----------------|
| **Need to add features ASAP** | Phase 1-2 (Foundation + Security) |
| **Have 2-3 months** | Incremental modernization (All 5 phases) |
| **Have 6+ months & budget** | Rewrite in Laravel |
| **Small team, limited PHP knowledge** | CodeIgniter 4 |
| **Want to learn modern PHP** | Incremental + Eloquent ORM |

---

## Next Steps

1. **Choose your path** (Incremental vs Rewrite)
2. **Set up Git** (if not already)
3. **Start with Phase 1** (Fix deprecated functions)
4. **Add one modern feature** (e.g., new login with modern code)
5. **Compare old vs new** (see the difference)
6. **Gradually migrate** module by module

---

## Conclusion

Your codebase has **good bones** (modular architecture, OOP, database abstraction) but needs **modern plumbing**. The incremental approach lets you:

- Keep existing features working
- Add new features easily with modern code
- Learn modern patterns gradually
- Reduce risk of breaking everything

**The "hell" of adding features comes from:**
1. Globals making code unpredictable
2. No autoloading (manual requires)
3. Mixed concerns (HTML + logic + data)
4. Deprecated functions breaking on new PHP
5. No testing (fear of breaking things)

**Modernization fixes all of these** while preserving your module system and domain logic.

Want help implementing any specific phase? Let me know!
