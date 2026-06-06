# Southeast Code Starter Kit

## Overview

This repository is the Southeast Code starter application for Laravel projects with Vue, Inertia, TypeScript, Tailwind CSS, Fortify, passkeys, and Wayfinder.

## Local Setup

This project is intended to run locally through Laravel Herd. Use Herd for PHP and Composer; npm should come from the Node version in `.nvmrc`.

```bash
herd composer install
npm install
cp .env.example .env
herd php artisan key:generate
herd php artisan migrate
herd php artisan storage:link
npm run build
```

## Verification

```bash
herd composer validate --strict
herd composer run lint:check
herd php artisan test
npm run lint:check
npm run format:check
npm run types:check
npm run build
```

## Development Plan

See [docs/development-plan.md](docs/development-plan.md).

## License

This starter kit is open-sourced software licensed under the MIT license.
