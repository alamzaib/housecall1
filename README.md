# Task Management System

## Overview

A full-stack task management system built as a take-home interview project. Authenticated users can create Projects, manage Tasks within those Projects, track task status and priority, and view a Dashboard summarizing their work. The application follows a strict layered architecture with SOLID principles applied throughout, server-side authorization on every mutation, and an automated Pest test suite covering the full behavioral surface.

## Features

- Authentication (registration, login, logout, guarded application area)
- Projects (create, view, update, delete — scoped to the authenticated user)
- Tasks (create, view, update, delete — nested within a Project)
- Task status (`todo`, `in_progress`, `completed`) with a dedicated status-change endpoint
- Task priority (`low`, `medium`, `high`)
- Dashboard (project/task statistics and a task-status distribution visualization)
- Authorization (policy-based ownership checks on every Project/Task action)
- Tests (50 Pest feature tests, 179 assertions)

## Tech Stack

- Laravel 12
- PHP 8.2
- MySQL
- React 18
- TypeScript
- Inertia.js 2
- Pest 3

## Installation

```bash
# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Copy and configure environment
cp .env.example .env
php artisan key:generate
```

Set the following in `.env` (MySQL, not SQLite):
EOD
EOF

Create the database in MySQL/phpMyAdmin, then:

```bash
php artisan migrate
```

Run the app (two terminals):

```bash
php artisan serve
npm run dev
```

Visit `http://127.0.0.1:8000`.

## Architecture

Every write and read for Projects and Tasks flows through the same layered chain:
Each layer has exactly one reason to change: a new field goes through the Form Request and DTO; a new business rule goes in the Service; a new query goes in the Repository; a new access rule goes in the Policy. Controllers stay thin — they orchestrate, they never validate, authorize inline, or query the database directly.

## SOLID Principles

- **SRP** — Each class has one reason to change. Form Requests validate, Policies authorize, Services hold business rules, Repositories persist. None of these responsibilities overlap.
- **OCP** — New task/project behavior is added by extending the Service layer, not by modifying Controllers or Repositories. The Repository contract stays stable as business logic evolves.
- **LSP** — Both repository implementations (`ProjectRepository`, `TaskRepository`) are fully substitutable for their interfaces; nothing outside the Repository depends on implementation-specific behavior.
- **ISP** — `ProjectRepositoryInterface` and `TaskRepositoryInterface` are narrow and specific to their entity — no fat, shared interface forcing either implementation to support methods it doesn't need.
- **DIP** — Services depend on `ProjectRepositoryInterface`/`TaskRepositoryInterface`, never on the concrete Eloquent-backed classes. The Laravel service container wires the concrete implementation in at runtime via a single binding in `AppServiceProvider`.

## Other Principles

- **DRY** — Shared UI (`ProjectForm`, `TaskForm`, badges) and shared Form Request rule shapes avoid duplicating logic across Create/Update paths.
- **KISS** — Status/priority stored as plain string columns with PHP enum casting, rather than native SQL `ENUM` types or a separate lookup table. Dashboard visualization is CSS bars, not a charting library.
- **YAGNI** — No pagination, no caching, no generic base Repository/DTO, no Service interfaces — none of these were added speculatively; they're deferred until an actual requirement demands them.
- **Separation of Concerns** — HTTP, validation, authorization, business logic, and persistence are each isolated in their own layer (see Architecture above).
- **Dependency Injection** — Every Service and Controller receives its dependencies via constructor injection; nothing is manually instantiated with `new`.
- **Strong typing** — PHP: DTOs are `final readonly` classes with typed properties, enums are backed and used everywhere instead of raw strings. TypeScript: every component has an explicit prop interface, no `any` anywhere in the codebase.

## Design Decisions

**Why Service Layer?** Separates business/application logic from both HTTP concerns (Controller) and persistence (Repository), so business rules can be tested and reused independently of how a request arrives or how data is stored.

**Why DTO?** Decouples the Service layer from `Illuminate\Http\Request`. A Service that only knows about `ProjectData`/`TaskData` can be called from a controller, a console command, or a test without any HTTP scaffolding.

**Why Repository?** Inverts the dependency between business logic and Eloquent. The Service depends on an interface, not a concrete ORM call — persistence could change without touching business rules.

**Why Policies?** Centralizes every ownership check (`user_id`, or `task.project.user_id` for the nested case) in one canonical place per model, enforced server-side on every relevant action — never trusted to the frontend.

**Why PHP Enums?** `TaskStatus`/`TaskPriority` make invalid values structurally impossible once inside the domain layer, are validated automatically via Laravel's `Enum` validation rule, and keep a single source of truth for valid values instead of scattered string literals.

**Why Inertia?** Gives a full React/TypeScript SPA experience while keeping routing, validation, and authorization entirely server-side in Laravel — no separate API layer, no duplicated validation logic between backend and frontend.

**Why MySQL?** Specified as a hard requirement for this assessment; also the natural fit for XAMPP's local development environment.

## Deliberately Not Used

- **CQRS** — Read and write paths are simple enough that separating them would add indirection without any corresponding benefit at this scale (YAGNI).
- **Event Sourcing** — No requirement for audit trails or state reconstruction from an event log; a standard CRUD data model is simpler and sufficient (KISS).
- **Microservices** — A single Laravel monolith is the appropriate scope for two related entities (Project, Task) behind one authentication boundary.
- **Redux/Zustand/React Query** — Inertia's own page-props model and `useForm` already provide all the state management this app needs; a global store would be unused complexity (YAGNI).
- **Unnecessary background jobs** — Every operation (create/update/delete/status-change) completes fast enough to run synchronously; queuing would add operational complexity with no user-facing benefit.
- **Unnecessary caching** — Dashboard statistics run two lightweight, indexed queries regardless of data volume; caching them now would be optimizing before there's any evidence of a performance problem (YAGNI).

## Testing

Run the full Pest feature test suite:

```bash
php artisan test
```

Run a specific suite:

```bash
php artisan test --filter=ProjectTest
php artisan test --filter=TaskTest
php artisan test --filter=DashboardTest
```

50 tests / 179 assertions cover: full CRUD for Projects and Tasks, task status changes, cross-user authorization (IDOR protection) on view/update/delete, validation of required fields and enum values, dashboard statistic accuracy and per-user data isolation, and unauthenticated access rejection across every protected route.

## Security

- **Authentication** — Laravel Breeze (registration, login, logout, email verification) guards every application route via the `auth` middleware.
- **Authorization** — Every Project/Task view, update, and delete action is checked against a Policy (`ProjectPolicy`, `TaskPolicy`) comparing the authenticated user's ID against the resource's owner — for Tasks, resolved through the parent Project's `user_id`.
- **Server-side validation** — All input is validated exclusively in Form Request classes (`StoreProjectRequest`, `UpdateTaskRequest`, etc.); the frontend never re-implements or duplicates these rules — it only displays whatever the server rejects.
- **Ownership checks** — Enforced identically whether an action originates from the UI or a raw HTTP request (curl/Postman); frontend button visibility is never relied upon for security.
- **CSRF** — Enforced by Laravel's default `VerifyCsrfToken` middleware; Inertia's `axios` client attaches the token automatically on every request.
- **Mass assignment protection** — Every Eloquent model declares an explicit `$fillable` allow-list; DTOs only ever emit the exact fields that allow-list expects.
