# E-Commerce Platform - Agent AI Guidelines

This document provides a comprehensive guide for the AI Agent working on this codebase. It describes the project architecture, tech stack, coding conventions, and operational procedures to ensure high-quality and consistent contributions.

## 1. Project Overview & Architecture

**Project Type**: A modern, intelligent e-commerce platform featuring an advanced AI decision engine, fraud detection, real-time analytics, and professional payment integration (VNPay).

**Key Modules**:
- **Customer Facing**: Shopping Experience, Cart & Checkout, Account Management (Orders, Wishlist, Addresses, Support Tickets).
- **Admin/Management (RBAC)**: Admin Dashboard (Reports, Audit Logs, Settings, Disputes), Staff Limited Management, Vendor Management (Own Products, Finance).
- **Electro AI Engine**: AI Decision Engine, Risk Management, Fraud Detection, User Behavior Profiling.

**Domain Architecture**:
- **Controllers**: Located in `app/Http/Controllers`. Highly divided by roles (`Admin\`, `Staff\`, `Vendor\`, `Customer\`, and public APIs).
- **Services**: Heavy use of Service classes (`app/Services/`) encapsulating business logic (e.g., `OrderService`, `AIDecisionEngine`, `CartService`).
- **Repositories**: Database abstraction using the Repository pattern (`app/Repositories/`).
- **Models**: Standard Eloquent models (`app/Models/`) representing the dense data schema.

## 2. Technology Stack & Environment Configurations

- **Backend**: Laravel 12, PHP 8.5.1
- **Database**: MySQL 8.0+
- **Frontend**: Blade Templates, Alpine.js (v3), Tailwind CSS (v3)
- **Real-time**: Laravel Reverb/Echo (configured via broadcasting)
- **Testing**: Pest (v4), PHPUnit (v12)
- **Core Ecosystem**: Laravel Prompts, Scout, Socialite, Breeze, Pint, Sail
- **Environment & Drivers**: 
  - **Session, Cache & Queue**: Configured to use the `database` driver.
  - **Redis**: Phpredis client configured for caching/queues if needed.
- **External Integrations & APIs**:
  - **Payment**: VNPay
  - **Authentication**: Google OAuth (via Laravel Socialite)
  - **AI Capabilities**: OpenAI API (Key configured for the Electro AI Engine)
  - **File Storage**: AWS S3 support configured for file storage

## 3. Strict Coding Conventions

### General PHP & Laravel
- **Typing**: ALWAYS use explicit return type declarations and appropriate type hints for method parameters.
- **Constructors**: Use PHP 8 constructor property promotion.
- **Enums**: Use TitleCase for Enum keys (e.g., `FavoritePerson`).
- **Control Structures**: Always use curly braces, even for single-line bodies.
- **Comments**: Prefer PHPDoc blocks over inline comments. Include array shape definitions when appropriate.
- **Code Formatting**: Run `vendor/bin/pint --dirty` before finalizing changes. Do not run `--test`, just fix it.

### Laravel 12 Specifics
- **CRITICAL**: Always use the `search-docs` tool for version-specific Laravel 12 documentation.
- **Structure**: Leverage `bootstrap/app.php` for middleware, exceptions, and routing. There is NO `app/Http/Kernel.php` or `app/Console/Kernel.php`.
- **Database**: When modifying a column in a migration, include *all* previously defined attributes to prevent dropping them. Use native limits for eager loading: `$query->latest()->limit(10);`.

### Database & Eloquent
- **Prefer Eloquent**: Use relationships and `Model::query()` over raw `DB::` queries.
- **Performance**: Prevent N+1 queries using eager loading.
- **Logic Placement**: Use Form Requests for validation instead of inline controller validation.

### Frontend
- **Tailwind CSS**: Check existing patterns before creating new ones. Use `search-docs` tool to refer to version 3 documentation. Activate `tailwindcss-development` skill when working on UI.
- **Building Assets**: If frontend changes aren't reflected, prompt the user or run `npm run build` or `npm run dev`.

## 4. Testing Protocols

- **Mandatory Testing**: Every change MUST be programmatically tested. Write new tests or update existing ones, then execute them.
- **Framework**: Use Pest 4. Create tests via `php artisan make:test --pest {name}`.
- **Execution**: Run targeted tests for speed: `php artisan test --compact --filter=testName`.
- **Activation**: Activate the `pest-testing` skill whenever creating/modifying tests.
- **Guidelines**: Use Model Factories and Seeders. Avoid manually creating test data when factories are available. Use `$this->faker` or `fake()` as per existing conventions.

## 5. Helpful Commands & Tools

- **Artisan**: Use `php artisan make:` for generic classes, models, migrations (via `list-artisan-commands`). Always pass `--no-interaction`.
- **Debugging**: Use `tinker` tool or `database-query` tool. View `browser-logs` for frontend debugging.
- **Dev Server**: The application uses Vite. For Dev Server execution: run `npm run dev` (Vite runs on `localhost:5173`). The application itself is accessed at `https://e-commerce.app`.

## 6. Development Workflow Rules

1. **Verify Before Coding**: Call `search-docs` to ensure you're using Laravel 12 and current package APIs.
2. **Review Context**: Check `resources/views` for current UI structures and existing Components before creating new ones.
3. **Format & Test**: Run Pint and Pest before declaring a task complete.
4. **Be Concise**: When answering the user, focus on the rationale and solution rather than overly detailed explanations of obvious steps.
