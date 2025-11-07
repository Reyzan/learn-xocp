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

### Path B: Incremental Modernization (3-4 months)
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

## Phase 1.5: PHP 8+ Migration (Week 2-3)

### Goal: Leverage PHP 8.0+ features for better performance, type safety, and developer experience

PHP 8+ introduced revolutionary features that make code cleaner, faster, and safer. This phase shows you how to migrate from PHP 7.4 to PHP 8+ and leverage new features.

### Why Upgrade to PHP 8+?

**Performance:**
- **2-3x faster** than PHP 7.4 (JIT compiler)
- 10-30% less memory usage
- Better opcache optimization

**Developer Experience:**
- Named arguments
- Constructor property promotion
- Match expressions
- Union types & nullsafe operator
- Attributes (annotations)
- Enums (PHP 8.1)
- Readonly properties

**Security:**
- Stricter type system
- Better error handling
- Fewer silent failures

### Step 1.5.1: Update PHP Version Requirement

Update `composer.json`:
```json
{
    "require": {
        "php": ">=8.1",
        "vlucas/phpdotenv": "^5.5",
        "monolog/monolog": "^3.0"
    }
}
```

### Step 1.5.2: Breaking Changes & Fixes

#### 🔴 Critical Breaking Changes

**1. Null to Non-Nullable Type Deprecation**

**Before (PHP 7.4 - works, PHP 8+ - error):**
```php
function setName(string $name) {
    $this->name = $name;
}

setName(null); // Fatal error in PHP 8+
```

**After:**
```php
function setName(?string $name) { // Nullable type
    $this->name = $name;
}

setName(null); // Now works
```

**2. String to Number Comparisons**

**Before (PHP 7.4 - loose comparison):**
```php
0 == "hello"; // true in PHP 7.4 (!!!)
```

**After (PHP 8+ - strict comparison):**
```php
0 == "hello"; // false in PHP 8+
// Use strict comparison always
0 === "hello"; // false
```

**3. Array Key Auto-increment**

**Before:**
```php
$array = [];
$array[] = "a";
$array["1"] = "b"; // String key
$array[] = "c";    // Gets index 1 in PHP 7.4 (overwrites!)
```

**After (PHP 8+):**
```php
// Gets index 2 in PHP 8+ (safer)
```

**Fix for XOCP:** Audit all dynamic array operations.

**4. `@` Error Suppression Operator**

PHP 8+ makes `@` less effective. **Replace in your codebase:**

**Before:**
```php
@mysql_connect($host, $user, $pass); // Bad practice
```

**After:**
```php
try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    throw $e;
}
```

### Step 1.5.3: PHP 8.0 Features to Use

#### 1. **Named Arguments** (Game Changer for XOCP Forms)

**Before:**
```php
// class/form/xocpform.php
$form->addElement(new XocpFormText('uname', 'Username', 50, 255, ''));
// What's 50? What's 255? Hard to remember!
```

**After:**
```php
$form->addElement(new XocpFormText(
    name: 'uname',
    caption: 'Username',
    size: 50,
    maxlength: 255,
    value: ''
));
// Much clearer!
```

**Update form classes:**
```php
// class/form/xocpformtext.php
class XocpFormText extends XocpFormElement {
    public function __construct(
        string $name,
        string $caption = '',
        int $size = 50,
        int $maxlength = 255,
        string $value = ''
    ) {
        $this->name = $name;
        $this->caption = $caption;
        $this->size = $size;
        $this->maxlength = $maxlength;
        $this->value = $value;
    }
}
```

#### 2. **Constructor Property Promotion** (Less Boilerplate)

**Before:**
```php
// class/xocpobject.php
class XocpObject {
    private $vars = [];
    private $db;
    private $table;

    public function __construct($table, $db) {
        $this->table = $table;
        $this->db = $db;
        $this->vars = [];
    }
}
```

**After (PHP 8+):**
```php
class XocpObject {
    public function __construct(
        private string $table,
        private DBConnection $db,
        private array $vars = []
    ) {
        // Properties automatically assigned!
    }
}
```

**Savings:** 50% less boilerplate code across all classes!

#### 3. **Nullsafe Operator** (Stop Null Checking Hell)

**Before:**
```php
// modules/project/project.php
$project = getProject($id);
if ($project !== null) {
    $owner = $project->getOwner();
    if ($owner !== null) {
        $email = $owner->getEmail();
        if ($email !== null) {
            echo $email;
        }
    }
}
```

**After (PHP 8+):**
```php
echo $project?->getOwner()?->getEmail() ?? 'No email';
// One line, safe, clean!
```

#### 4. **Match Expression** (Better than Switch)

**Before:**
```php
// include/functions.php
function getUserRoleLabel($role) {
    switch ($role) {
        case 1:
            return 'Admin';
        case 2:
            return 'Moderator';
        case 3:
            return 'User';
        default:
            return 'Guest';
    }
}
```

**After (PHP 8+):**
```php
function getUserRoleLabel($role) {
    return match($role) {
        1 => 'Admin',
        2 => 'Moderator',
        3 => 'User',
        default => 'Guest',
    }; // Auto-return, strict comparison, no fall-through
}
```

#### 5. **Union Types** (More Precise Type Hints)

**Before:**
```php
// class/database/database.php
function query($sql) { // What does this return?
    // Could be result object, false, or array
}
```

**After (PHP 8+):**
```php
function query(string $sql): PDOStatement|false {
    // Clear return type
}

function getUser(int|string $identifier): ?User {
    // Accepts int ID or string username
}
```

#### 6. **Mixed Type** (Better than No Type)

**Before:**
```php
function getVar($key) { // No type hints
    return $this->vars[$key] ?? null;
}
```

**After:**
```php
function getVar(string $key): mixed { // Explicitly mixed
    return $this->vars[$key] ?? null;
}
```

#### 7. **Attributes** (Metadata for Classes)

Use attributes instead of docblock comments:

**Before:**
```php
/**
 * @table users
 * @primary_key uid
 */
class User extends XocpObject {
}
```

**After (PHP 8+):**
```php
#[Table('users')]
#[PrimaryKey('uid')]
class User extends XocpObject {
}

// Can be read programmatically!
$reflection = new ReflectionClass(User::class);
$table = $reflection->getAttributes(Table::class)[0]->newInstance();
echo $table->name; // 'users'
```

### Step 1.5.4: PHP 8.1 Features

#### 1. **Enums** (Type-Safe Constants)

**Before:**
```php
// config.php or constants
define('USER_ROLE_ADMIN', 1);
define('USER_ROLE_MODERATOR', 2);
define('USER_ROLE_USER', 3);

function checkRole($role) {
    if ($role === USER_ROLE_ADMIN) { // Typo-prone
        // ...
    }
}
```

**After (PHP 8.1+):**
```php
// src/Enums/UserRole.php
enum UserRole: int {
    case Admin = 1;
    case Moderator = 2;
    case User = 3;
    case Guest = 4;

    public function label(): string {
        return match($this) {
            self::Admin => 'Administrator',
            self::Moderator => 'Moderator',
            self::User => 'Registered User',
            self::Guest => 'Guest',
        };
    }

    public function canModerate(): bool {
        return match($this) {
            self::Admin, self::Moderator => true,
            default => false,
        };
    }
}

// Usage
function checkRole(UserRole $role) { // Type-safe!
    if ($role === UserRole::Admin) {
        // IDE autocomplete, no typos possible
    }
}

$role = UserRole::from($_SESSION['role_id']); // Safe conversion
echo $role->label(); // "Administrator"
```

**Apply to XOCP:**
```php
enum ModulePermission: string {
    case Read = 'read';
    case Write = 'write';
    case Admin = 'admin';
}

enum DatabaseDriver: string {
    case MySQL = 'mysql';
    case PostgreSQL = 'postgresql';
}
```

#### 2. **Readonly Properties** (Immutable Objects)

**Before:**
```php
class Config {
    public $dbHost;

    public function __construct($dbHost) {
        $this->dbHost = $dbHost;
    }
}

$config = new Config('localhost');
$config->dbHost = 'hacked.com'; // Oops! Mutable
```

**After (PHP 8.1+):**
```php
class Config {
    public function __construct(
        public readonly string $dbHost,
        public readonly string $dbName,
        public readonly string $dbUser,
    ) {}
}

$config = new Config('localhost', 'xocp', 'root');
$config->dbHost = 'hacked.com'; // Fatal error! Immutable
```

#### 3. **First-Class Callable Syntax**

**Before:**
```php
array_map(function($user) {
    return $user->getName();
}, $users);
```

**After (PHP 8.1+):**
```php
array_map($user->getName(...), $users);
```

#### 4. **Array Unpacking with String Keys**

**Before:**
```php
$defaults = ['theme' => 'plain', 'lang' => 'english'];
$custom = ['theme' => 'dark'];
$config = array_merge($defaults, $custom);
```

**After (PHP 8.1+):**
```php
$config = [...$defaults, ...$custom]; // Cleaner
```

### Step 1.5.5: PHP 8.2 Features

#### 1. **Readonly Classes** (All Properties Readonly)

**Before:**
```php
class UserDTO {
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $role
    ) {}
}
```

**After (PHP 8.2+):**
```php
readonly class UserDTO {
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $role
    ) {}
    // All properties automatically readonly!
}
```

#### 2. **Disjunctive Normal Form (DNF) Types**

```php
function process((User&Admin)|(Guest&Verified) $user) {
    // Complex type combinations
}
```

#### 3. **True Type** (More Specific)

```php
function isValid(): true { // Only returns true, never false
    return true;
}
```

### Step 1.5.6: PHP 8.3 Features

#### 1. **Typed Class Constants**

**Before:**
```php
class Database {
    const DRIVER = 'mysql'; // No type
}
```

**After (PHP 8.3+):**
```php
class Database {
    const string DRIVER = 'mysql';
    const int MAX_CONNECTIONS = 100;
}
```

#### 2. **Override Attribute** (Safety for Inheritance)

```php
class XocpUser extends XocpObject {
    #[Override]
    public function load(int $id): bool {
        // Compiler ensures parent has this method
    }
}
```

### Step 1.5.7: Modernize XOCP Classes with PHP 8+

#### XocpObject with PHP 8+ Features

**Before (PHP 7.4):**
```php
// class/xocpobject.php
class XocpObject {
    var $vars = [];
    var $db;
    var $table;

    function __construct() {
        $this->vars = [];
    }

    function initVar($key, $data_type, $value = null) {
        $this->vars[$key] = [
            'type' => $data_type,
            'value' => $value
        ];
    }

    function getVar($key) {
        return isset($this->vars[$key]['value'])
            ? $this->vars[$key]['value']
            : null;
    }
}
```

**After (PHP 8.1+):**
```php
// src/Database/Model.php
abstract readonly class Model {
    public function __construct(
        protected PDO $db,
        protected string $table,
        protected string $primaryKey = 'id'
    ) {}

    public function find(int|string $id): ?static {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?"
        );
        $stmt->execute([$id]);

        $data = $stmt->fetch();
        return $data ? static::fromArray($data) : null;
    }

    abstract public static function fromArray(array $data): static;
}

// src/Models/User.php
readonly class User extends Model {
    public function __construct(
        PDO $db,
        public int $uid,
        public string $uname,
        public string $email,
        public UserRole $role,
        public ?string $name = null,
    ) {
        parent::__construct($db, 'users', 'uid');
    }

    public static function fromArray(array $data): static {
        return new self(
            db: app('db'),
            uid: (int)$data['uid'],
            uname: $data['uname'],
            email: $data['email'],
            role: UserRole::from($data['role']),
            name: $data['name'] ?? null,
        );
    }

    public function can(ModulePermission $permission, string $module): bool {
        return match($this->role) {
            UserRole::Admin => true,
            UserRole::Moderator => $permission !== ModulePermission::Admin,
            default => $permission === ModulePermission::Read,
        };
    }
}

// Usage - so much cleaner!
$user = User::find(123);
echo $user?->name ?? 'Anonymous'; // Nullsafe
if ($user?->can(ModulePermission::Write, 'project')) {
    // ...
}
```

### Step 1.5.8: Migration Checklist

**Week 2: Preparation**
- [ ] Upgrade local environment to PHP 8.1+
- [ ] Run `composer update` with PHP 8.1 requirements
- [ ] Enable all error reporting
- [ ] Create test suite (if not done in Phase 5)

**Week 3: Code Updates**
- [ ] Replace `var` with `public/private/protected`
- [ ] Add type hints to all function parameters
- [ ] Add return type declarations
- [ ] Replace switch with match where applicable
- [ ] Create enums for constants
- [ ] Use constructor property promotion
- [ ] Add readonly where applicable
- [ ] Use nullsafe operator for nested calls
- [ ] Replace `strpos() === false` with `str_contains()`
- [ ] Replace `strlen()` checks with `empty()`

**Week 4: Testing & Optimization**
- [ ] Run full test suite
- [ ] Enable opcache with JIT
- [ ] Benchmark performance (should be 2-3x faster)
- [ ] Fix any remaining warnings

### Step 1.5.9: PHP 8+ String Functions (Bonus)

PHP 8+ added convenient string functions:

**Before (PHP 7.4):**
```php
if (strpos($email, '@') !== false) {
    // Contains @
}

if (strpos($url, 'https://') === 0) {
    // Starts with https
}

if (substr($filename, -4) === '.php') {
    // Ends with .php
}
```

**After (PHP 8+):**
```php
if (str_contains($email, '@')) {
    // Much cleaner!
}

if (str_starts_with($url, 'https://')) {
    // Clear intent
}

if (str_ends_with($filename, '.php')) {
    // Readable
}
```

### Step 1.5.10: Performance: Enable JIT Compiler

Add to `php.ini` or `.htaccess`:
```ini
opcache.enable=1
opcache.jit_buffer_size=100M
opcache.jit=1255
```

**Benchmark results you can expect:**
- Complex calculations: 2-3x faster
- String operations: 20-30% faster
- Array operations: 10-20% faster
- Database-heavy apps: 10-15% faster overall

### Step 1.5.11: PHP 8+ Migration Example

**Complete module migration example:**

**Before (modules/calendar/calendar.php - PHP 7.4):**
```php
<?php
require_once "../../config.php";
require_once XOCP_DOC_ROOT . "/include/common.php";

global $xocpDB, $xocp_user;

$month = isset($HTTP_GET_VARS['month']) ? intval($HTTP_GET_VARS['month']) : date('m');
$year = isset($HTTP_GET_VARS['year']) ? intval($HTTP_GET_VARS['year']) : date('Y');

if ($month < 1 || $month > 12) {
    $month = date('m');
}

$sql = "SELECT * FROM " . XOCP_PREFIX . "calendar_events
        WHERE MONTH(event_date) = " . $month . "
        AND YEAR(event_date) = " . $year;
$result = $xocpDB->query($sql);

$events = [];
while ($row = $xocpDB->fetchArray($result)) {
    $events[] = $row;
}

echo "<h1>Calendar - " . date('F Y', mktime(0,0,0,$month,1,$year)) . "</h1>";
foreach ($events as $event) {
    echo "<div>" . $event['title'] . " - " . $event['event_date'] . "</div>";
}
?>
```

**After (src/Controllers/CalendarController.php - PHP 8.1+):**
```php
<?php
namespace Xocp\Controllers;

use Xocp\Models\CalendarEvent;
use DateTimeImmutable;

readonly class CalendarController extends BaseController {
    public function __construct(
        private CalendarEvent $eventModel
    ) {}

    public function index(int $month = null, int $year = null): void {
        $now = new DateTimeImmutable();
        $month ??= (int)$now->format('m');
        $year ??= (int)$now->format('Y');

        $month = match(true) {
            $month < 1 => 1,
            $month > 12 => 12,
            default => $month,
        };

        $events = $this->eventModel->getByMonth(
            month: $month,
            year: $year
        );

        $this->render('calendar/index', [
            'month' => $month,
            'year' => $year,
            'monthName' => $now->setDate($year, $month, 1)->format('F Y'),
            'events' => $events,
        ]);
    }
}
```

**Benefits of PHP 8+ version:**
- ✅ No globals
- ✅ Type-safe parameters
- ✅ Null coalescing assignment (`??=`)
- ✅ Match expression for validation
- ✅ Named arguments
- ✅ Constructor property promotion
- ✅ Readonly class (immutable)
- ✅ No SQL injection (model handles queries)
- ✅ Separation of concerns
- ✅ Testable

### Step 1.5.12: IDE Configuration for PHP 8+

Update `.vscode/settings.json` or PHPStorm settings:
```json
{
    "php.version": "8.1",
    "php.suggest.basic": true,
    "intelephense.environment.phpVersion": "8.1.0"
}
```

---

## Phase 2: Security Hardening (Week 4)

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

## Phase 3: Modern Architecture (Week 5-7)

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

## Phase 4: Modern ORM/Query Builder (Week 8-9)

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

## Phase 5: Testing & CI/CD (Week 10-12)

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
| **Have 3-4 months** | Incremental modernization (All phases inc. PHP 8+) |
| **Have 6+ months & budget** | Rewrite in Laravel |
| **Small team, limited PHP knowledge** | CodeIgniter 4 |
| **Want to learn modern PHP** | Incremental + PHP 8.1+ + Eloquent ORM |
| **Already on PHP 8+** | Skip to Phase 2, focus on security first |

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
