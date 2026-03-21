# CODEBUDDY.md

This file provides guidance to CodeBuddy Code when working with code in this repository.

## Project Overview

A web-based software learning platform built with PHP 8.0+ and Slim Framework 4. Users learn software (e.g., GIMP) through interactive tutorials covering interface recognition, basic operations, and workflow simulation. The platform also includes user authentication, a forum, and an admin panel.

## Common Commands

```bash
# Install dependencies
composer install

# Run all tests
composer test

# Run a single test file
vendor/bin/phpunit tests/Unit/CalculatorTest.php

# Run a single test method
vendor/bin/phpunit --filter testAddition

# Run only unit or integration tests
vendor/bin/phpunit --testsuite unit
vendor/bin/phpunit --testsuite integration

# Code style check (only checks src/)
composer cs-check

# Auto-fix code style (only fixes src/)
composer cs-fix

# Start the built-in PHP development server (Slim app)
composer start
# or: php -S localhost:8000 -t public/

# Initialize the database (creates tables for users, forum, etc.)
php init-db.php
```

## Architecture

### Dual Entry Points

The project has two distinct interfaces:

1. **Slim Framework Web API** (`public/index.php`) — The primary entry point. A full REST API with routes under `/api/` for software learning, user auth, forum, and admin features. Also serves frontend HTML pages from `public/*.html`.

2. **CLI Learning Mode** (referenced in docs via `php public/index.php gimp`) — Not currently implemented in the code. The CLI modules (`InterfaceRecognizer`, `BasicOperations`, `WorkflowSimulator`) exist and read from stdin, but `public/index.php` is entirely a Slim web application.

### Dependency Graph

```
public/index.php (Slim App)
├── Controllers/
│   ├── SoftwareController  — reads config/*.php files, no DB dependency
│   ├── UserController      — uses AuthService + Database (PDO)
│   ├── ForumController     — uses ForumModel + Database (PDO)
│   └── AdminController     — uses AuthService + Database (PDO)
├── Middleware/
│   ├── AuthMiddleware      — checks PHP session for user_id
│   └── AdminMiddleware     — checks session + queries DB for admin role
├── Models/Services/
│   ├── SoftwareInfo        — plain PHP model, hydrated from config arrays
│   ├── AuthService         — register/login/logout/password ops via PDO
│   ├── ForumModel          — CRUD for posts, replies, likes via PDO
│   ├── Config              — loads .env and config/app.php
│   ├── Database            — singleton PDO, hard-coded to Termux MySQL socket
│   └── Logger              — simple file logger
└── config/
    ├── app.php             — database/app/logging config (reads .env)
    ├── gimp.php            — example software tutorial data
    └── software_template.php — template for adding new software tutorials
```

### Key Architectural Details

- **Database**: MySQL via PDO singleton (`src/Database.php`). Connection string is hard-coded for Termux (`/data/data/com.termux/files/usr/var/run/mysqld.sock`). Connection config comes from `config/app.php` which reads `.env`.
- **Authentication**: PHP sessions (`$_SESSION`). Password hashing via `password_hash()`. Roles: `user` and `admin`.
- **Software tutorials are file-based**: Each software is a PHP config file in `config/` that returns an array. `SoftwareController` reads these directly from disk — no database storage for tutorial content.
- **Forum and admin features require MySQL**: The forum (posts, replies, likes) and admin panel (user management, stats) all depend on MySQL tables created by `init-db.php`.

### API Route Structure

All API routes are under `/api/`:
- `/api/software` — list/get software tutorials (config-file backed)
- `/api/software/{id}/interface|operations|workflows` — tutorial content
- `/api/software/{id}/simulate/{operation}` — simulate an operation
- `/api/auth/*` — register, login, logout, user profile (session-based)
- `/api/forum/*` — posts, replies, likes (MySQL-backed)
- `/api/admin/*` — user/post management, stats (admin-only)

Protected routes use `AuthMiddleware` (requires login) or `AdminMiddleware` (requires admin role).

## Conventions

- **PSR-4 autoloading**: `App\` → `src/`, `Tests\` → `tests/`
- **PSR-12 code style**: enforced by PHP_CodeSniffer on `src/` only
- **PHP 8.0 type hints** on all method parameters and return values
- **Test class naming**: `Tests\Unit\{ClassName}Test`, method naming: `test{MethodName}`
- **Config file naming**: lowercase, underscored, English software name (e.g., `gimp.php`)
- **Software types**: `image_processing`, `video_editor`, `audio_editor`, `text_editor`
- **Workflow difficulty**: `beginner`, `intermediate`, `advanced`

## Adding a New Software Tutorial

1. Copy `config/software_template.php` to `config/{software_name}.php`
2. Define `name`, `version`, `description`, `type`, `interface_elements`, `operations`, `workflows`
3. The tutorial is immediately available via the API at `/api/software/{software_name}`

No code changes or database updates needed — tutorials are purely config-driven.
