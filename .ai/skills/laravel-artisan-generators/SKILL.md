---
name: laravel-artisan-generators
description: Use whenever creating Laravel PHP files or classes, including controllers, models, migrations, requests, jobs, commands, events, listeners, policies, resources, rules, seeders, middleware, providers, casts, factories, tests, or similar generated application files.
---

# Laravel Artisan Generators

Do not hand-create Laravel PHP classes when an Artisan generator exists. Start with the framework generator, then edit the generated file.

## Workflow

1. Run `php artisan list make --no-interaction` when unsure which generator applies.
2. Check options with `php artisan make:{type} --help`.
3. Generate the file with `php artisan make:{type} ... --no-interaction`.
4. Edit the generated file to match project conventions.
5. Run the relevant formatter, static analysis, and tests.

## Defaults

- Prefer the most specific generator available, such as `make:controller --resource`, `make:model --factory`, `make:request`, or `make:test`.
- Generated tests should use Pest syntax. Do not pass `--phpunit` unless a consuming project explicitly opts out of this starter kit convention.
- Use custom files in `stubs/` as the source of generated defaults.
- If no generator exists, use `php artisan make:class --no-interaction` before falling back to creating a PHP file manually.
- Only create a PHP class manually when Artisan has no suitable generator.
