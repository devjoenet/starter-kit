---
- PHP 8.5+ (`composer.json` currently requires `^8.5`)
- Laravel 13.7+
- Node 24.16.0+
- npm 11.13.0 via `packageManager`
- MySQL 8+ for local application data
- SQLite in-memory for the current PHPUnit test configuration
- Redis available in environment configuration, not yet the default cache/queue/session store
- Vue 3
- TypeScript
- Inertia.js
- Tailwind CSS 4
- Reka UI / shadcn-vue style primitives
- Lucide Icons
---

# Southeast Code Starter Kit Development Plan

## Purpose

This document defines the current development plan for the Southeast Code Laravel/Vue starter kit.

The repository is now available and is no longer being treated as a fork of `laravel/vue-starter-kit`. It should be maintained as its own starter kit with explicit Southeast Code defaults, documentation, dependencies, and release criteria.

The objective is practical, maintainable business software. Maximum abstraction is how teams accidentally invent a worse framework before lunch.

## Current Repository Baseline

Already present:

- Laravel 13 application structure
- Vue 3, Inertia, TypeScript, Vite, Tailwind CSS 4
- Fortify authentication flows
- Registration, login, password reset, email verification, password confirmation
- Two-factor authentication and passkeys
- Wayfinder route generation
- Pint configuration
- ESLint configuration
- Prettier configuration
- Strict TypeScript mode
- npm package lock and Node 24.16.0 runtime metadata
- npm 11.13.0 package-manager metadata
- Reka UI / shadcn-vue inspired component primitives
- Lucide icon package
- Rector configuration and dependency
- PHPStan/Larastan configuration and dependency
- Laravel Boost and Pao development tooling
- Basic layouts, settings pages, dashboard, and auth pages
- PHPUnit test structure and existing auth/settings tests
- GitHub Actions CI workflow

Not yet present:

- Spatie Data / TypeScript Transformer
- Spatie Permission
- Spatie Activity Log
- Generic `app/Data`, `app/Enums`, `app/Integrations`, `app/Support`, and `app/ViewModels` directories
- Role/permission administration UI
- Data table framework
- Reusable form wrapper
- Full Southeast Code design-system documentation

## Root Config and Dependency Audit

Checked root config and dotfiles against `composer.json` and `package.json`.

### Missing or Mismatched Packages

- No current root config imports a missing Composer or npm package.

### Runtime Version Alignment

- `composer.json` requires PHP `^8.5`.
- `.nvmrc` pins Node `24.16.0`.
- `package.json` requires Node `>=24.16.0`.
- `package.json` declares npm `11.13.0` through `packageManager`.
- Local PHP and Composer commands should run through Herd.
- npm commands should run from the Node version in `.nvmrc`; `herd npm` is not available in the current local Herd setup.

PHP 8.5 is a deliberately high floor for a starter kit. Keep it only if consuming projects are expected to start on PHP 8.5; lowering it later will require a Composer constraint change plus a full Rector, Pint, PHPUnit, and frontend verification pass.

### Present and Satisfied Package References

- `vite.config.ts`
  - Uses `@inertiajs/vite`, `@laravel/vite-plugin-wayfinder`, `@tailwindcss/vite`, `@vitejs/plugin-vue`, `laravel-vite-plugin`, and `vite`.
  - All are present in `package.json`.
- `.prettierrc`
  - Uses `prettier-plugin-tailwindcss`.
  - Present in `package.json`.
- `eslint.config.js`
  - Uses `@stylistic/eslint-plugin`, `@vue/eslint-config-typescript`, `eslint-config-prettier`, `eslint-plugin-import`, and `eslint-plugin-vue`.
  - All are present in `package.json`.
- `rector.php`
  - Uses `rector/rector`.
  - Present in `composer.json` `require-dev`.
- `boost.json`
  - Uses Laravel Boost agent/tooling configuration.
  - `laravel/boost` is present in `composer.json` `require-dev`.
- `components.json`
  - Uses shadcn-vue/Tailwind CSS 4 component generation settings.
  - Tailwind config is intentionally blank because Tailwind CSS 4 does not require a `tailwind.config.js` file.
- `package.json`
  - Uses an npm override to keep transitive PostCSS resolution on the patched `^8.5.15` line.

### Config Cleanup Notes

- `.npmrc` sets `ignore-scripts=true`; keep only if that is an intentional security default and does not block needed package postinstall behavior.
- Composer `setup` and `dev` scripts call `npm` directly. That is fine as long as the Node 24.16.0 bin directory is active before running those Composer scripts locally.

## Development Standards

### Laravel 13 Attributes and Types

Prefer Laravel 13 native PHP attributes for framework configuration when Laravel provides a direct equivalent.

Target areas:

- Eloquent model configuration
- Routing and controller middleware/authorization
- Queue and background job controls
- Service container and dependency injection
- Artisan console command metadata

Use attributes to keep configuration close to the class, method, parameter, or property it affects. Do not force attributes where Laravel does not provide an equivalent or where a method is required for dynamic behavior.

Examples:

- Use model attributes such as `#[Fillable]`, `#[Guarded]`, `#[Hidden]`, `#[Table]`, `#[UseFactory]`, `#[UsePolicy]`, `#[ScopedBy]`, and related Eloquent attributes where they replace static model configuration cleanly.
- Use controller attributes such as `#[Middleware]` and `#[Authorize]` instead of controller middleware methods or route-level authorization middleware when the behavior belongs to the controller/action.
- Use queue attributes such as `#[Connection]`, `#[Queue]`, `#[Tries]`, `#[Backoff]`, `#[Timeout]`, `#[MaxExceptions]`, `#[FailOnTimeout]`, `#[WithoutRelations]`, and related job attributes instead of queue configuration properties when adding jobs.
- Use container attributes such as `#[Config]`, `#[CurrentUser]`, `#[Storage]`, `#[Cache]`, `#[DB]`, `#[Log]`, `#[Give]`, `#[RouteParameter]`, `#[Tag]`, `#[Singleton]`, and `#[Scoped]` where they make dependency wiring explicit.
- Use console attributes such as `#[Signature]`, `#[Description]`, `#[Help]`, `#[Aliases]`, `#[Usage]`, and `#[Hidden]` for Artisan commands.

Typing rules:

- Use typed properties, parameters, and return values everywhere PHP can represent the type accurately.
- Use detailed PHPDoc when native PHP types are not expressive enough, especially for arrays, collections, Eloquent builders, paginator payloads, validated request data, closures, generators, and iterable service collections.
- Prefer precise generics over vague arrays, for example `array<string, mixed>`, `Collection<int, User>`, or `Builder<User>`.
- Keep PHPStan/Larastan passing without baselines or inline ignores unless a framework limitation leaves no honest alternative.

### Controllers

Controllers should remain thin.

Controllers should:

- Validate
- Authorize
- Dispatch actions
- Return responses

Controllers should not contain business logic.

Preferred:

```php
return CreateUserAction::run($data);
```

Avoid:

```php
public function store()
{
    // 200 lines of logic
}
```

### Actions

Business logic belongs in Actions.

Examples:

```text
CreateUserAction
UpdateUserAction
InviteUserAction
ExportReportAction
```

Rules:

- One Action = one business operation
- Actions should be independently testable
- Actions should not return Inertia responses

### Data Objects

Application boundaries should use DTOs once Spatie Data is installed.

Examples:

```text
CreateUserData
UpdateProjectData
CreateInvoiceData
```

DTOs should:

- Validate input
- Cast values
- Normalize structures
- Generate TypeScript types where appropriate

### Authorization

Use:

- Policies
- Gates
- Spatie Permission

Avoid:

- Inline role checks
- Hardcoded permission strings throughout the codebase

Preferred:

```php
$this->authorize('update', $project);
```

### Services

Services should encapsulate reusable technical concerns.

Examples:

```text
PdfService
EmailService
ImportService
```

Services are not business workflows. Business workflows belong in Actions.

### Frontend Components

Favor reusable application components and extend the existing UI primitives instead of duplicating page-specific markup.

Already present:

- Alert
- Avatar
- Badge
- Breadcrumb
- Button
- Card
- Checkbox
- Collapsible
- Dialog
- Dropdown menu
- Input
- Input OTP
- Label
- Navigation menu
- Select
- Separator
- Sheet
- Sidebar
- Skeleton
- Sonner/toast shell
- Spinner
- Tooltip

Still needed:

- Textarea
- Switch
- PageHeader
- EmptyState
- DataTable
- Form wrapper

### State Management

Default:

- Inertia props
- Vue composables

Introduce Pinia only when application complexity requires it.

### Database Access

Preferred:

```php
Project::query()
```

Avoid repository patterns unless a project demonstrates a clear need. Eloquent remains the primary data access layer.

### Events

Use domain events for important completed application behavior.

Examples:

```text
UserInvited
InvoicePaid
ProjectArchived
```

### Queues

Queue:

- Emails
- Reports
- Imports
- Exports
- Notifications

Avoid long-running synchronous requests.

### Logging

Log:

- Business events
- Failures
- Integration activity

Avoid excessive debug logging in production.

### Testing

Prioritize testing:

- Actions
- Policies
- Critical workflows
- Imports
- Exports

Coverage is less important than confidence.

### Integrations

Integrations should be isolated.

Structure:

```text
app/
└── Integrations/
```

Each integration owns:

```text
Actions
Data
Enums
Exceptions
Client
```

Third-party APIs should not leak directly into application logic.

## Roadmap

## Phase 0 - Repository Identity and Baseline Cleanup

- [x] Rename Composer package away from `laravel/vue-starter-kit`.
- [x] Update Composer description, keywords, and project metadata.
- [x] Rewrite `README.md` for Southeast Code usage.
- [x] Remove upstream Laravel contribution/Maestro references.
- [x] Set public package identity to `devjoenet/starter-kit`.
- [ ] Replace or remove remaining default starter pages.
- [ ] Replace starter branding with Southeast Code branding.
- [x] Fix `.env.example` typo and duplicate AWS path-style setting.
- [x] Decide whether Chisel remains part of this starter kit or is removed after auth features are fixed in place.
- [x] Choose npm or pnpm as the package manager.
- [x] Add the matching lock file.
- [x] Update Composer setup scripts to use the chosen package manager.
- [x] Reconcile or delete `eslint.config.ts`.
- [x] Reconcile `components.json` with Tailwind CSS 4 and the missing `tailwind.config.js`.
- [x] Pin PHP and Node runtime requirements in Composer, npm metadata, and `.nvmrc`.
- [x] Document Herd PHP/Composer usage and npm-from-Node setup behavior.

## Phase 1 - Core Package Adoption

### Backend Packages

- [ ] Install `spatie/laravel-data`.
- [ ] Install `spatie/typescript-transformer`.
- [ ] Install `spatie/laravel-permission`.
- [ ] Install `spatie/laravel-activitylog`.
- [ ] Publish and configure package migrations/config where needed.
- [ ] Add project conventions for DTOs, generated TypeScript, permissions, and activity logging.

### Development Packages

- [x] Install `rector/rector` or remove `rector.php`.
- [x] Install Laravel Boost/Pao development tooling.
- [x] Install `larastan/larastan`.
- [x] Add `phpstan.neon`.
- [ ] Decide whether to keep PHPUnit only or adopt Pest.
- [x] Remove `pestphp/pest-plugin` from Composer `allow-plugins` if Pest is not adopted.

### Framework Convention Audit

- [ ] Audit Laravel 13 attribute coverage against the installed framework attributes.
- [ ] Convert Eloquent model static configuration to native attributes where Laravel provides direct replacements.
- [ ] Convert controller middleware and policy authorization declarations to native attributes where the behavior belongs to a controller or action.
- [ ] Convert queued job configuration properties to native queue attributes when jobs are added.
- [ ] Convert service container contextual binding/provider boilerplate to native container attributes where possible.
- [ ] Convert Artisan command signatures, descriptions, aliases, help, usage, and visibility metadata to console attributes when commands are added.
- [ ] Add typed properties, method arguments, and return values wherever PHP can express the type accurately.
- [ ] Add detailed PHPDoc generics and shape annotations where native PHP types are not specific enough.
- [ ] Raise PHPStan/Larastan strictness after the attribute/type audit if the codebase passes without noisy framework false positives.

## Phase 2 - Runtime Infrastructure

- [ ] Decide whether Redis should become the default cache, queue, and/or session backend.
- [ ] Configure Redis if promoted from optional environment support.
- [ ] Configure queue driver defaults.
- [ ] Configure scheduler tasks and document local/production execution.
- [ ] Add operational defaults for mail, storage, and local services.

## Phase 3 - Application Architecture

- [ ] Create `app/Actions` structure beyond Fortify actions.
- [ ] Create `app/Data` after Spatie Data is installed.
- [ ] Create `app/Enums` when the first enum is needed.
- [ ] Create `app/Integrations` when the first external integration is added.
- [ ] Create `app/Support` only for reusable support code with a clear owner.
- [ ] Create `app/ViewModels` only if pages need explicit view model objects.
- [ ] Add a sample Action tied to a real workflow.
- [ ] Add a sample Data object tied to a real request or response.
- [ ] Add a sample Policy tied to a real model.
- [ ] Add a sample Event tied to a completed business action.

## Phase 4 - Authorization and Administration

- [ ] Publish Spatie Permission migrations.
- [ ] Define role and permission naming conventions.
- [ ] Seed default roles.
- [ ] Seed default permissions.
- [ ] Decide whether team support is required by default.
- [ ] Build user management UI.
- [ ] Build role management UI.
- [ ] Build permission management UI.

## Phase 5 - Design System

- [ ] Define Southeast Code color tokens.
- [ ] Define semantic colors.
- [ ] Define typography scale.
- [ ] Define radius scale.
- [ ] Define spacing scale.
- [ ] Add `Textarea`.
- [ ] Add `Switch`.
- [ ] Add `PageHeader`.
- [ ] Add `EmptyState`.
- [ ] Document component usage patterns.

## Phase 6 - Data and Form Components

### Tables

- [ ] Build DataTable component.
- [ ] Add sorting.
- [ ] Add filtering.
- [ ] Add pagination.
- [ ] Add bulk actions.
- [ ] Add saved filters only after a real use case exists.

### Forms

- [ ] Build form wrapper.
- [ ] Add validation helpers.
- [ ] Add consistent error handling.
- [ ] Add loading and disabled states.
- [ ] Add generated TypeScript integration where useful.

## Phase 7 - Application Features

### Activity Log

- [ ] Configure Activity Log package.
- [ ] Define which models/events are tracked by default.
- [ ] Build activity feed component.
- [ ] Build activity timeline.
- [ ] Build activity viewer.

### Notifications

- [ ] Finalize toast system behavior.
- [ ] Build notification center only if needed by starter-kit consumers.

### File Handling

- [ ] Build upload component.
- [ ] Add file preview support.
- [ ] Add media management foundation only if a concrete package or pattern is chosen.

## Phase 8 - Operations and Observability

- [ ] Decide whether Telescope belongs in the starter kit or only in consuming apps.
- [ ] Decide whether Pulse belongs in the starter kit or only in consuming apps.
- [ ] Decide whether Sentry belongs in the starter kit or only in consuming apps.
- [ ] Add structured logging conventions.
- [ ] Add request ID support.
- [ ] Add correlation ID support for queued/integration work.

## Phase 9 - CI and Documentation

### CI

- [x] Add GitHub Actions workflow.
- [x] Run Composer validation.
- [x] Run Composer dependency audit.
- [x] Run Rector validation.
- [x] Run Pint validation.
- [x] Run PHPStan/Larastan.
- [x] Run PHPUnit.
- [x] Run npm lint check.
- [x] Run npm format check.
- [x] Run npm dependency audit.
- [x] Generate Wayfinder routes/forms before TypeScript validation.
- [x] Run TypeScript check.
- [x] Run frontend build verification.

### Documentation

- [x] Write basic development setup guide in `README.md`.
- [ ] Expand development setup guide with production-like local service notes.
- [ ] Write architecture guide.
- [ ] Write UI component guide.
- [ ] Write authorization guide.
- [ ] Write contribution guide.
- [x] Document package-manager choice.
- [ ] Document generated file workflows for Wayfinder and TypeScript types.

### Templates

- [ ] CRUD template.
- [ ] Resource template.
- [ ] Integration template.
- [ ] Action template.

## Version 1.0 Release Criteria

- [x] Project metadata no longer references the upstream starter kit.
- [x] README is accurate for current Southeast Code local setup.
- [x] Package manager and lock file are settled.
- [x] PHP and Node runtime requirements are pinned.
- [x] Root config files do not reference missing packages or missing config files.
- [ ] Core package installation is complete.
- [ ] Laravel 13 attribute and type audit is complete.
- [ ] DTO architecture is documented and demonstrated.
- [ ] Action architecture is documented and demonstrated.
- [ ] Authorization system is installed and demonstrated.
- [ ] User/role/permission management is implemented.
- [ ] Design system foundation is complete.
- [ ] Core UI components are complete.
- [ ] DataTable framework is complete.
- [x] Static analysis tooling passes, including Rector and PHPStan/Larastan.
- [ ] CI pipeline passes.
- [ ] Documentation is complete enough for a new project to start from this kit without archaeological work.
